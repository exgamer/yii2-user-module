<?php
namespace concepture\yii2user\traits;

use concepture\yii2user\services\AuthService;
use concepture\yii2user\services\UserCredentialService;
use concepture\yii2user\services\UserRoleHandbookService;
use concepture\yii2user\services\UserRoleService;
use concepture\yii2user\services\UserService;
use Yii;

/**
 * Trait ServicesTrait
 *
 * @author citizenzet <exgamer@live.ru>
 */
trait ServicesTrait
{
    /**
     * @return UserService
     */
    public function userService()
    {
        return Yii::$app->userService;
    }

    /**
     * @return UserCredentialService
     */
    public function userCredentialService()
    {
        return Yii::$app->userCredentialService;
    }

    /**
     * @return AuthService
     */
    public function authService()
    {
        return Yii::$app->authService;
    }

    /**
     * @return UserRoleHandbookService
     */
    public function userRoleHandbookService()
    {
        return Yii::$app->userRoleHandbookService;
    }

    /**
     * @return UserRoleService
     */
    public function userRoleService()
    {
        return Yii::$app->userRoleService;
    }
}

