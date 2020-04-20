<?php
namespace concepture\yii2user\rbac\rules;

use yii\rbac\Rule;

/**
 * Проверка сущности на владельца
 * Переменную $params мы передаем сюда, когда вызываем \Yii::$app->user->can('role', $param = ['model' => $model]);
 */
class StaffRule extends Rule
{
    public $name = 'staff-rule';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $model = $params['model'] ?? null;
        if (! $model){
            return true;
        }

        if ($model->hasAttribute('owner_id') && $user !== $model->owner_id){
            return false;
        }

        return true;
    }
}