<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2handbook\services\DomainService;
use concepture\yii2user\search\UserAuthAssignmentSearch;
use concepture\yii2user\services\RbacService;
use concepture\yii2user\services\UserDomainAssignmentService;
use frontend\search\post\PostIndexSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use concepture\yii2handbook\services\EntityTypePositionSortService;
use concepture\yii2logic\filters\AjaxFilter;
use kamaelkz\yii2admin\v1\enum\FlashAlertEnum;

/**
 * Class UserDomainAssignmentController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserDomainAssignmentController extends Controller
{
    /**
     * @return UserDomainAssignmentService
     */
    public function userDomainAssignmentService()
    {
        return Yii::$app->userDomainAssignmentService;
    }

    /**
     * @return DomainService
     */
    public function domainService()
    {
        return Yii::$app->domainService;
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

    public function actionIndex($user_id)
    {
        $userDomainsDataProvider = $this->userDomainAssignmentService()->getDataProvider(
            ['user_id' => $user_id],
            [
                'pagination' => [
                    'pageSize' => 30,
                ]
            ],
        );
        $userDomains = ArrayHelper::index($userDomainsDataProvider->getModels(), 'domain_id');
        $domains = $this->domainService()->getDomainsData();
        foreach ($domains as $id => $domain) {
            if (isset($userDomains[$id])) {
                unset($domains[$id]);
                $userDomains[$id]->country_caption = $domain['country_caption'];
            }
        }

        $domainsDataProvider = new ArrayDataProvider([
            'allModels' =>  $domains,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('index', [
            'user_id' => $user_id,
            'domainsDataProvider' => $domainsDataProvider,
            'userDomainsDataProvider' => $userDomainsDataProvider,
        ]);
    }

    /**
     * @param $user_id
     * @param $domain_id
     * @param $access
     * @return string
     */
    public function actionCreate($user_id, $domain_id, $access)
    {
        try {
            if (($this->userDomainAssignmentService()->assign($user_id, $domain_id, $access)) !== false) {

                return $this->responseNotify();
            }
        } catch (\Exception $e) {
            return $this->responseNotify(FlashAlertEnum::WARNING, $e->getMessage());
        }

        return $this->responseNotify(FlashAlertEnum::WARNING, $this->getErrorFlash());
    }

    /**
     * @param $user_id
     * @param $domain_id
     * @param $access
     * @return string
     */
    public function actionDelete($user_id, $domain_id, $access)
    {
        try {
            $result = $this->userDomainAssignmentService()->revoke($user_id, $domain_id, $access);
            if ($result === false){
                throw new \Exception(Yii::t('yii2admin', 'Невозможно удалить доступ !'));
            }

            return $this->responseNotify();
        } catch (\Exception $e) {
            return $this->responseNotify(FlashAlertEnum::WARNING, $e->getMessage());
        }
    }
}