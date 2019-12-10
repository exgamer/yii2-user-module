<?php
namespace concepture\yii2user\web\controllers;

use concepture\yii2logic\actions\web\localized\StatusChangeAction;
use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2user\forms\EmailCredentialForm;
use concepture\yii2user\forms\UserCredentialForm;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class UserCredentialController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialController extends Controller
{
    protected function getAccessRules()
    {
        return [
            [
                'actions' => ['index', 'view','create', 'update', 'status-change'],
                'allow' => true,
                'roles' => [UserRoleEnum::ADMIN],
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);

        return array_merge($actions,[
            'status-change' => StatusChangeAction::class
        ]);
    }

    public function actionCreate()
    {
        $model = new EmailCredentialForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($result = $this->getService()->create($model)) != false) {

                return $this->redirect('index');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $originModel = $this->getService()->findById($id);
        if (!$originModel){
            throw new NotFoundHttpException();
        }

        $model = new UserCredentialForm();
        $model->setAttributes($originModel->attributes, false);
        $model->validation = null;
        if ($model->load(Yii::$app->request->post())) {
            $originModel->setAttributes($model->attributes);
            if ($model->validate(null, true, $originModel)) {
                $model->validation = Yii::$app->security->generatePasswordHash($model->validation);
                if (($result = $this->getService()->save($model, $originModel)) != false) {

                    return $this->redirect('index');
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'originModel' => $originModel,
        ]);
    }
}