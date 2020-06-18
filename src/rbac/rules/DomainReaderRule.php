<?php
namespace concepture\yii2user\rbac\rules;

use concepture\yii2logic\enum\AccessTypeEnum;
use Yii;

/**
 * Проверка на доступ к домену
 * Когда вызываем \Yii::$app->user->can('role'); Будет проверен текущий домен
 * Переменную $params мы передаем сюда, когда вызываем \Yii::$app->user->can('role', $param = ['domain_id' => 2]);
 * Если не передавать будет проверен текущий домен
 */
class DomainReaderRule extends DomainRule
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

        $access = $this->getAccess($user, $domainId);

        if(! isset($access[AccessTypeEnum::READ])) {
            return false;
        }

        return true;
    }
}
