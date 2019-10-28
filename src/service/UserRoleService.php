<?php
namespace concepture\yii2user\service;

use concepture\yii2logic\services\Service;


/**
 * UserRoleService
 *
 */
class UserRoleService extends Service
{
    public function getRolesByUserId($user_id)
    {
        return $this->getQuery()->where(['user_id' => $user_id])->indexBy('role.name')->all();
    }
}
