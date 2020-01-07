<?php

namespace concepture\yii2user\web\controllers;

use Yii;
use concepture\yii2user\forms\UserRoleForm;
use concepture\yii2user\search\UserCredentialSearch;
use concepture\yii2user\search\UserRoleSearch;
use concepture\yii2user\traits\ServicesTrait;
use yii\web\NotFoundHttpException;

/**
 * Class UserRoleController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleController extends Controller
{
    use ServicesTrait;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['create']);
        unset($actions['index']);
        unset($actions['delete']);
        unset($actions['view']);

        return $actions;
    }

    public function actionIndex($user_id)
    {
        $searchModel = Yii::createObject(UserRoleSearch::class);
        $searchModel->user_id = $user_id;
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider =  $this->getService()->getDataProvider([], [], $searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $this->userService()->findById($user_id)
        ]);
    }

    public function actionCreate($user_id)
    {
        $model = new UserRoleForm();
        $model->user_id = $user_id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($result = $this->getService()->create($model)) != false) {

                return $this->redirect(['index', 'user_id' => $model->user_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $this->userService()->findById($user_id)
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->userRoleService()->findById($id);
        $user_id = $model->user_id;
        if (!$model){
            throw new NotFoundHttpException();
        }

        $this->getService()->delete($model);

        return $this->redirect(['index', 'user_id' => $user_id]);
    }
}
