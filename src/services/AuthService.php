<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\helpers\MailerHelper;
use concepture\yii2user\forms\EmailPasswordResetRequestForm;
use concepture\yii2user\forms\PasswordResetForm;
use concepture\yii2user\forms\SignInForm;
use concepture\yii2user\helpers\SsoHelper;
use concepture\yii2user\services\helpers\DefaultAuthHelper;
use concepture\yii2user\services\helpers\SsoAuthHelper;
use concepture\yii2user\services\interfaces\AuthHelperInterface;
use concepture\yii2user\traits\ServicesTrait;
use Exception;
use Yii;
use concepture\yii2user\forms\SignUpForm;
use concepture\yii2logic\services\Service;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\enum\IsDeletedEnum;

/**
 * Сервис содержит бизнес логику для работы с авторизацией/регистрацией пользователя
 *
 * Class AuthService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class AuthService extends Service  implements AuthHelperInterface
{
    use ServicesTrait;

    protected $authHelper;

    /**
     * @return AuthHelperInterface
     */
    public function getAuthHelper()
    {
        if (! $this->authHelper){
            $this->authHelper = SsoHelper::isSsoEnabled() ? new SsoAuthHelper() : new DefaultAuthHelper();
        }

        return $this->authHelper;
    }

    /**
     * Регистрация пользователя
     *
     * @param SignUpForm $form
     * @return ActiveRecord|boolean
     * @throws Exception
     */
    public function signUp(SignUpForm $form)
    {
        return $this->getAuthHelper()->signUp($form);
    }



    /**
     * Авторизация пользователя
     *
     * @param SignInForm $form
     * @return bool
     * @throws Exception
     */
    public function signIn(SignInForm $form)
    {
        return $this->getAuthHelper()->signIn($form);
    }

    /**
     * Логаут
     *
     * @return mixed
     */
    public function signOut()
    {
        return $this->getAuthHelper()->signOut();
    }

    /**
     * Посылка письма со ссылкой на сброс пароля
     *
     * @param EmailPasswordResetRequestForm $form
     * @return bool
     * @throws Exception
     */
    public function sendPasswordResetEmail(EmailPasswordResetRequestForm $form)
    {
        return $this->getAuthHelper()->sendPasswordResetEmail($form);
    }

    /**
     * смена пароля
     *
     * @param PasswordResetForm $form
     * @return bool
     * @throws Exception
     */
    public function changePassword(PasswordResetForm $form)
    {
        return $this->getAuthHelper()->changePassword($form);
    }

    /**
     * Лействия после успешной авторизации по стороннему апи
     *
     * @param yii\authclient\OAuth2 $client
     * @return mixed
     */
    public function onSocialAuthSuccess($client)
    {
        return $this->getAuthHelper()->onSocialAuthSuccess($client);
    }
}