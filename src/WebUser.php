<?php

namespace concepture\yii2user;

use Yii;
use yii\web\User;

use concepture\yii2user\services\UserRoleService;

/**
 * Класс описывающий атворизованного юзера
 *
 * Class WebUser
 * @package concepture\yii2user
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class WebUser extends User
{
    /**
     * @var array
     */
    protected $roles = [];

    /**
     * @return UserRoleService
     */
    protected function getUserRoleService()
    {
        return Yii::$app->userRoleService;
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if(! $this->isGuest) {
            $this->roles = $this->getUserRoleService()->getRolesByUserId($this->getIdentity()->getId());
        }
    }

    /**
     * @TODO костылек для начала работы
     *
     * @param $permissionName
     * @param array $params
     * @param bool $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if (! $this->identity){
            return false;
        }

        if (isset($this->roles[$permissionName])){
            return true;
        }

        return false;
    }
}
