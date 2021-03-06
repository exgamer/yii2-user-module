<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\search\UserAuthAssignmentSearch;
use concepture\yii2user\services\RbacService;
use Yii;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use concepture\yii2handbook\services\EntityTypePositionSortService;
use concepture\yii2logic\filters\AjaxFilter;
use kamaelkz\yii2admin\v1\enum\FlashAlertEnum;

/**
 * Class UserAuthAssignmentController
 * @package concepture\yii2handbook\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAuthAssignmentController extends Controller
{
    /**
     * @return RbacService
     */
    public function rbacService()
    {
        return Yii::$app->rbacService;
    }

    /**
     * @inheritDoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['view'], $actions['delete']);

        return $actions;
    }

    /**
     * @inheritDoc
     */
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['onlyAjax'] = [
//            'class' => AjaxFilter::class,
//            'except' => [
//                'options'
//            ],
//        ];
//
//        return $behaviors;
//    }

    public function actionIndex($user_id, $type = Item::TYPE_ROLE)
    {
        switch ($type){
            case Item::TYPE_ROLE:
                $itemsMethod = 'getRoles';
                $userItemsMethod = 'getRolesByUser';
                $title = Yii::t('common', 'Назначение ролей');
                $left_side_header = Yii::t('common', 'Список ролей');
                $right_side_header = Yii::t('common', 'Назначенные роли');
                $item_caption = Yii::t('common', 'Роль');
                break;
            case Item::TYPE_PERMISSION:
                $itemsMethod = 'getPermissions';
                $userItemsMethod = 'getPermissionsByUser';
                $title = Yii::t('common', 'Назначение полномочий');
                $left_side_header = Yii::t('common', 'Список полномочий');
                $right_side_header = Yii::t('common', 'Назначенные полномочия');
                $item_caption = Yii::t('common', 'Полномочие');
                break;
        }
        $roles = $this->rbacService()->{$itemsMethod}();
        $roleSearchModel = new UserAuthAssignmentSearch();
        $roleSearchModel->load(Yii::$app->request->queryParams,'');
        $rolesDataProvider =  new ArrayDataProvider([
            'allModels' =>  $roles,
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $userRolesDataProvider = new ArrayDataProvider([
            'allModels' =>  $this->rbacService()->{$userItemsMethod}($user_id),
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        /**
         * @TODO костылек для поиска
         * т.к. в authmanager нет поиска
         */
        if ($roleSearchModel->name){
            $result = [];
            foreach ($roles as $key => $model){
                $pos = strpos(strtolower($model->name), strtolower($roleSearchModel->name));
                if ($pos === false) {
                    continue;
                }

                $result[$key] = $model;
            }

            $roles = $result;
            $rolesDataProvider =  new ArrayDataProvider([
                'allModels' =>  $roles,
                'sort' => [
                    'attributes' => ['name'],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);

        }

        $usedRoles = [];
        if (count($this->rbacService()->{$userItemsMethod}($user_id))){
            foreach ($this->rbacService()->{$userItemsMethod}($user_id) as $model){
                $usedRoles[$model->name] = $model->name;
            }
        }

        if (! empty($usedRoles)){
            $res = [];
            foreach ($roles as $key => $model){
                if (isset($usedRoles[$model->name])){
                    continue;
                }

                $res[] = $model;
            }

            $rolesDataProvider =  new ArrayDataProvider([
                'allModels' =>  $res,
                'sort' => [
                    'attributes' => ['name'],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);
        }

        return $this->render('index', [
            'roleSearchModel' => $roleSearchModel,
            'user_id' => $user_id,
            'type' => $type,
            'title' => $title,
            'item_caption' => $item_caption,
            'left_side_header' => $left_side_header,
            'right_side_header' => $right_side_header,
            'rolesDataProvider' => $rolesDataProvider,
            'userRolesDataProvider' => $userRolesDataProvider,
        ]);
    }

    /**
     * @param $user_id
     * @param $role
     * @return string
     */
    public function actionCreate($user_id, $role)
    {
        try {
            if (($this->rbacService()->assign($user_id, $role)) !== false) {

                return $this->responseNotify();
            }
        } catch (\Exception $e) {
            return $this->responseNotify(FlashAlertEnum::WARNING, $e->getMessage());
        }

        return $this->responseNotify(FlashAlertEnum::WARNING, $this->getErrorFlash());
    }

    /**
     * @param $user_id
     * @param $role
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDelete($user_id, $role)
    {
        try {
            $result = $this->rbacService()->revoke($user_id, $role);
            if ($result === false){
                throw new \Exception(Yii::t('common', 'Невозможно удалить полномочие, которое не назначено напрямую!'));
            }

            return $this->responseNotify();
        } catch (\Exception $e) {
            return $this->responseNotify(FlashAlertEnum::WARNING, $e->getMessage());
        }
    }
}