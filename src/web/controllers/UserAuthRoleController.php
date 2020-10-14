<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\forms\UserAuthRoleForm;
use Yii;
use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2logic\actions\web\AutocompleteListAction;
use concepture\yii2logic\actions\web\StatusChangeAction;
use concepture\yii2logic\actions\web\UndeleteAction;
use kamaelkz\yii2admin\v1\modules\audit\actions\AuditAction;
use kamaelkz\yii2admin\v1\modules\audit\actions\AuditRollbackAction;
use kamaelkz\yii2cdnuploader\actions\web\ImageDeleteAction;
use kamaelkz\yii2cdnuploader\actions\web\ImageUploadAction;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class UserAuthRolesController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAuthRoleController extends Controller
{
//    /**
//     * @return array
//     */
//    protected function getAccessRules()
//    {
//        return ArrayHelper::merge(
//            parent::getAccessRules(),
//            [
//                [
//                    'actions' => ['index', 'create','delete'],
//                    'allow' => true,
//                    'roles' => [UserRoleEnum::ADMIN],
//                ],
//            ],
//        );
//    }

    public function actions()
    {
        return [];
    }

    public function actionIndex()
    {
        $dataProvider =  new ArrayDataProvider([
            'allModels' =>  Yii::$app->authManager->getRoles(),
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new UserAuthRoleForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($result =  Yii::$app->rbacService->addRole($model)) != false) {

                return $this->redirect('index');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($name)
    {
        Yii::$app->rbacService->removeRole($name);

        return $this->redirect('index');
    }
}
