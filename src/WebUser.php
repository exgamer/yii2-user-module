<?php
namespace concepture\yii2user;

use Yii;
use yii\web\User;

/**
 * Класс описывающий атворизованного юзера
 *
 * Class WebUser
 * @package concepture\yii2user
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class WebUser extends User
{
//    /**
//     * @TODO костылек для начала работы
//     *
//     * @param $permissionName
//     * @param array $params
//     * @param bool $allowCaching
//     * @return bool
//     */
//    public function can($permissionName, $params = [], $allowCaching = true)
//    {
//        if (! $this->identity){
//            return false;
//        }
//
//        $roles = Yii::$app->userRoleService->getRolesByUserId($this->identity->id);
//        if (isset($roles[$permissionName])){
//            return true;
//        }
//
//        return false;
//    }
}
