<?php
namespace concepture\yii2user\rbac\rules;

use concepture\yii2logic\enum\AccessTypeEnum;
use Yii;
use yii\rbac\Rule;

/**
 * Проверка на доступ к домену
 * Когда вызываем \Yii::$app->user->can('role'); Будет проверен текущий домен
 * Переменную $params мы передаем сюда, когда вызываем \Yii::$app->user->can('role', $param = ['domain_id' => 2]);
 * Если не передавать будет проверен текущий домен
 */
class DomainReaderRule extends Rule
{
    public $name = 'domain-reader-rule';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $domainId = $params['domain_id'] ?? null;
        if (! $domainId) {
            $domainId = Yii::$app->domainService->getCurrentDomainId();
        }

        $access = Yii::$app->userDomainAssignmentService->getOneByCondition([
            'user_id' => $user,
            'domain_id' => $domainId,
            'access' => AccessTypeEnum::READ,
        ]);

        if(! $access) {
            return false;
        }

        return true;
    }
}
