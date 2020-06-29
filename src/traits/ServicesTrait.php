<?php
namespace concepture\yii2user\traits;

use concepture\yii2user\services\AuthService;
use concepture\yii2user\services\EmailHandbookService;
use concepture\yii2user\services\RbacService;
use concepture\yii2user\services\UserAccountOperationService;
use concepture\yii2user\services\UserAccountService;
use concepture\yii2user\services\UserCredentialService;
use concepture\yii2user\services\UserDomainAssignmentService;
use concepture\yii2user\services\UserRoleHandbookService;
use concepture\yii2user\services\UserRoleService;
use concepture\yii2user\services\UserService;
use concepture\yii2user\services\UserSocialAuthService;
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
     * @return UserSocialAuthService
     */
    public function userSocialAuthService()
    {
        return Yii::$app->userSocialAuthService;
    }

    /**
     * @return AuthService
     */
    public function authService()
    {
        return Yii::$app->authService;
    }

    /**
     * @return UserRoleService
     */
    public function userRoleService()
    {
        return Yii::$app->userRoleService;
    }

    /**
     * @return EmailHandbookService
     */
    public function emailHandbookService()
    {
        return Yii::$app->emailHandbookService;
    }

    /**
     * @return UserDomainAssignmentService
     */
    public function userDomainAssignmentService()
    {
        return Yii::$app->userDomainAssignmentService;
    }

    /**
     * @return RbacService
     */
    public function rbacService()
    {
        return Yii::$app->rbacService;
    }
}
