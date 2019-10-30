<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\services\Service;


/**
 * Сервис содержит бизнес логику для работы с ролям ипользователя
 *
 * Class UserRoleService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleService extends Service
{
    public function getRolesByUserId($user_id)
    {
        return $this->getQuery()->where(['user_id' => $user_id])->indexBy('role.name')->all();
    }
}
