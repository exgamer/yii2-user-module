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
                $title = Yii::t('yii2admin', 'Назначение ролей');
                $left_side_header = Yii::t('yii2admin', 'Список ролей');
                $right_side_header = Yii::t('yii2admin', 'Назначенные роли');
                $item_caption = Yii::t('yii2admin', 'Роль');
                break;
            case Item::TYPE_PERMISSION:
                $itemsMethod = 'getPermissions';
                $userItemsMethod = 'getPermissionsByUser';
                $title = Yii::t('yii2admin', 'Назначение полномочий');
                $left_side_header = Yii::t('yii2admin', 'Список полномочий');
                $right_side_header = Yii::t('yii2admin', 'Назначенные полномочия');
                $item_caption = Yii::t('yii2admin', 'Полномочие');
                break;
        }

        $roleSearchModel = new UserAuthAssignmentSearch();
        $roleSearchModel->load(Yii::$app->request->queryParams,'');
        $rolesDataProvider =  new ArrayDataProvider([
            'allModels' =>  $this->rbacService()->{$itemsMethod}(),
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
            foreach ($rolesDataProvider->getModels() as $key => $model){
                $pos = strpos(strtolower($model->name), strtolower($roleSearchModel->name));
                if ($pos === false) {
                    continue;
                }

                $result[] = $model;
            }

            $rolesDataProvider =  new ArrayDataProvider([
                'allModels' =>  $result,
                'sort' => [
                    'attributes' => ['name'],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);
        }

        $usedRoles = [];
        if (count($userRolesDataProvider->getModels())){
            foreach ($userRolesDataProvider->getModels() as $model){
                $usedRoles[$model->name] = $model->name;
            }
        }

        if (! empty($usedRoles)){
            $roles = [];
            foreach ($rolesDataProvider->getModels() as $key => $model){
                if (isset($usedRoles[$model->name])){
                    continue;
                }

                $roles[] = $model;
            }

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
                throw new \Exception(Yii::t('yii2admin', 'Невозможно удалить полномочие, которое не назначено напрямую!'));
            }

            return $this->responseNotify();
        } catch (\Exception $e) {
            return $this->responseNotify(FlashAlertEnum::WARNING, $e->getMessage());
        }
    }
}
