<?php
namespace concepture\yii2user\rbac\rules;

use yii\rbac\Rule;

/**
 * Проверка на доступ к домену
 * Переменную $params мы передаем сюда, когда вызываем \Yii::$app->user->can('role', $param = ['model' => $model]);
 */
class DomainRule extends Rule
{
    public $name = 'domain-rule';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {


        return true;
    }
}