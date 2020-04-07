<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\enum\UserRoleEnum;
use kamaelkz\yii2admin\v1\controllers\BaseController;
use kamaelkz\yii2admin\v1\modules\audit\actions\AuditAction;
use kamaelkz\yii2admin\v1\modules\audit\actions\AuditRollbackAction;
use kamaelkz\yii2admin\v1\modules\audit\services\AuditService;
use yii\helpers\ArrayHelper;

/**
 * Базовый контроллер для модуля пользователя
 *
 * Class Controller
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
abstract class Controller extends BaseController
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (
            $action->id == 'update'
            && method_exists($action->controller, 'getService')
            && AuditService::isAuditAllowed($modelClass = $action->controller->getService()->getRelatedModelClass())
        ) {
            $this->getView()->viewHelper()->pushPageHeader(
                [AuditAction::actionName(), 'id' => \Yii::$app->request->get('id')],
                \Yii::t('yii2user', 'Аудит'),
                'icon-eye'
            );
        }
        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    protected function getAccessRules()
    {
        return ArrayHelper::merge(
            parent::getAccessRules(),
            [
                [
                    'actions' => ['index', 'create','update', 'view','delete'],
                    'allow' => true,
                    'roles' => [UserRoleEnum::ADMIN],
                ],
                [
                    'actions' => [
                        AuditAction::actionName(),
                        AuditRollbackAction::actionName(),
                    ],
                    'allow' => true,
                    'roles' => [
                        // TODO change
                        UserRoleEnum::ADMIN,
                    ],
                ],
            ],
        );
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        return ArrayHelper::merge($actions,[
            AuditAction::actionName() => AuditAction::class,
            AuditRollbackAction::actionName() => AuditRollbackAction::class,
        ]);
    }
}
