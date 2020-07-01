<?php

namespace concepture\yii2user\web\controllers;

use Yii;
use concepture\yii2logic\enum\ScenarioEnum;
use concepture\yii2logic\helpers\AccessHelper;
use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2logic\actions\web\AutocompleteListAction;
use concepture\yii2logic\actions\web\StatusChangeAction;
use concepture\yii2logic\actions\web\UndeleteAction;
use kamaelkz\yii2admin\v1\helpers\RequestHelper;
use kamaelkz\yii2cdnuploader\actions\web\ImageDeleteAction;
use kamaelkz\yii2cdnuploader\actions\web\ImageUploadAction;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserController extends Controller
{
//    protected function getAccessRules()
//    {
//        return [
//            [
//                'actions' => ['index', 'create','update', 'view','delete', 'list', 'undelete', 'status-change', 'image-upload', 'image-delete'],
//                'allow' => true,
//                'roles' => [UserRoleEnum::ADMIN],
//            ]
//        ];
//    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        $actions['list'] = AutocompleteListAction::class;
        $actions['status-change'] = StatusChangeAction::class;
        $actions['undelete'] = UndeleteAction::class;
        $actions['image-upload'] = ImageUploadAction::class;
        $actions['image-delete'] = ImageDeleteAction::class;

        return $actions;
    }

    public function actionUpdate($id, $data = null)
    {
        $originModel = $this->getService()->findById($id);
        if (!$originModel){
            throw new NotFoundHttpException();
        }

        if (! AccessHelper::checkAccess('update', ['model' => $originModel])){
            throw new \yii\web\ForbiddenHttpException(Yii::t("core", "You are not the owner"));
        }

        $model = $this->getForm();
        $model->scenario = ScenarioEnum::UPDATE;
        $model->setAttributes($originModel->attributes, false);

        if (method_exists($model, 'customizeForm')) {
            $model->customizeForm($originModel);
        }

        if ($model->load(Yii::$app->request->post())) {
            $originModel->setAttributes($model->attributes);
            if ($model->validate(null, true, $originModel)) {
                if (($result = $this->getService()->update($model, $originModel)) !== false) {
                    # todo: объеденить все условия редиректов, в переопределенной функции redirect базового контролера ядра (logic)
                    if ( RequestHelper::isMagicModal()){
                        return $this->responseJson([
                            'data' => $result,
                        ]);
                    }
                    if (Yii::$app->request->post(RequestHelper::REDIRECT_BTN_PARAM)) {
                        $redirectStore = $this->redirectStoreUrl();
                        if($redirectStore) {
                            return $redirectStore;
                        }

                        # todo: криво пашет
                        return $this->redirectPrevious(['index']);
                    }
                }
            }

            $model->addErrors($originModel->getErrors());
        }

        return $this->render('update', [
            'model' => $model,
            'originModel' => $originModel,
            'data' => $data,
        ]);
    }
}
