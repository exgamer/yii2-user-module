<?php
namespace concepture\yii2user\web\controllers;

use concepture\yii2logic\enum\AccessEnum;
use concepture\yii2logic\enum\PermissionEnum;
use concepture\yii2logic\helpers\AccessHelper;
use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2user\forms\ChangePasswordForm;
use concepture\yii2user\forms\EmailCredentialForm;
use concepture\yii2user\forms\UserCredentialDomainBanForm;
use concepture\yii2user\forms\UserCredentialForm;
use concepture\yii2user\search\UserCredentialSearch;
use concepture\yii2user\traits\ServicesTrait;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class UserCredentialController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialController extends Controller
{
    use ServicesTrait;

    protected function getAccessRules()
    {
        return ArrayHelper::merge(
            parent::getAccessRules(),
            [
                [
                    'actions' => [
                        'ban-domain',
                        'unban-domain',
                    ],
                    'allow' => true,
                    'roles' => [
                        AccessEnum::ADMIN,
                        AccessEnum::SUPERADMIN,
                        AccessHelper::getAccessPermission($this, PermissionEnum::EDITOR),
                        AccessHelper::getDomainAccessPermission($this, PermissionEnum::EDITOR)
                    ],
                ]
            ]
        );
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);

        return $actions;
    }

    public function actionCreate($user_id)
    {
        $model = new EmailCredentialForm();
        $model->user_id = $user_id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($result = $this->getService()->create($model)) != false) {

                return $this->redirect(['index' , 'user_id' => $user_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $this->userService()->findById($user_id)
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
                $form = Yii::createObject(ChangePasswordForm::class);
                $form->identity = $originModel->identity;
                $form->new_password = $model->validation ;
                if (($result = Yii::$app->authService->changePassword($form)) != false) {

                    return $this->redirect(['index' , 'user_id' => $originModel->user_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'originModel' => $originModel,
            'user' => $this->userService()->findById($originModel->user_id)
        ]);
    }


    public function actionUnbanDomain($id)
    {
        $originModel = $this->getService()->findById($id);
        if (!$originModel){
            throw new NotFoundHttpException();
        }

        $domains = $originModel->banned_domains ?? [];
        $enabledDomains = $this->domainService()->getEnabledDomainData();
        foreach ($enabledDomains as $domain_id => $value) {
            if (in_array($domain_id, $domains)) {
                continue;
            }

            unset($enabledDomains[$domain_id]);
        }

        $domainsArray = ArrayHelper::map($enabledDomains, 'domain_id', 'country_caption');
        $model = new UserCredentialDomainBanForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $result = $this->userCredentialService()->unbanDomain($originModel->user_id, $model->domain_id);

                return $this->redirect(['index' , 'user_id' => $originModel->user_id]);
            }
        }

        $title = Yii::t('common', 'Разблокировать ' . $originModel->identity);

        return $this->render('ban_domain', [
            'model' => $model,
            'originModel' => $originModel,
            'domainsArray' => $domainsArray,
            'title' => $title,
            'user' => $this->userService()->findById($originModel->user_id)
        ]);
    }

    public function actionBanDomain($id)
    {
        $originModel = $this->getService()->findById($id);
        if (!$originModel){
            throw new NotFoundHttpException();
        }

        $domains = $originModel->banned_domains ?? [];
        $enabledDomains = $this->domainService()->getEnabledDomainData();
        foreach ($domains as $domain_id) {
            unset($enabledDomains[$domain_id]);
        }

        $domainsArray = ArrayHelper::map($enabledDomains, 'domain_id', 'country_caption');
        $model = new UserCredentialDomainBanForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $result = $this->userCredentialService()->banDomain($originModel->user_id, $model->domain_id);

                return $this->redirect(['index' , 'user_id' => $originModel->user_id]);
            }
        }

        $title = Yii::t('common', 'Заблокировать ' . $originModel->identity);

        return $this->render('ban_domain', [
            'model' => $model,
            'originModel' => $originModel,
            'domainsArray' => $domainsArray,
            'title' => $title,
            'user' => $this->userService()->findById($originModel->user_id)
        ]);
    }

    public function actionIndex($user_id)
    {
        $searchModel = Yii::createObject(UserCredentialSearch::class);
        $searchModel->user_id = $user_id;
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider =  $this->getService()->getDataProvider([], [], $searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $this->userService()->findById($user_id)
        ]);
    }

    public function actionStatusChange($id, $status)
    {
        $model = $this->userCredentialService()->findById($id);
        $user_id = $model->user_id;
        if (!$model){
            throw new NotFoundHttpException();
        }

        $this->getService()->statusChange($model, $status);

        return $this->redirect(['index', 'user_id' => $user_id]);
    }
}