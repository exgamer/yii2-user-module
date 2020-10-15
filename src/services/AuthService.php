<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\helpers\MailerHelper;
use concepture\yii2user\forms\ChangePasswordForm;
use concepture\yii2user\forms\CredentialConfirmForm;
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

    public $authHelper = DefaultAuthHelper::class;

    /**
     * @return AuthHelperInterface
     */
    public function getAuthHelper()
    {
        if ($this->authHelper && ! is_object($this->authHelper)) {
            $this->authHelper = Yii::createObject($this->authHelper);
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
     * Подтверждение учетки
     *
     * @param CredentialConfirmForm $form
     * @return boolean
     * @throws Exception
     */
    public function confirmCredential(CredentialConfirmForm $form)
    {
        return $this->getAuthHelper()->confirmCredential($form);
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
     * сброс пароля
     *
     * @param PasswordResetForm $form
     * @return bool
     * @throws Exception
     */
    public function resetPassword(PasswordResetForm $form)
    {
        return $this->getAuthHelper()->resetPassword($form);
    }

    /**
     * Смена пароля
     *
     * @param ChangePasswordForm $form
     */
    public function changePassword(ChangePasswordForm $form)
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

    /**
     * Возвращает email из аттрибутов пользователя дял авторизации по соц сети
     *
     * @param $client
     * @return |null
     */
    public function getEmailFromClient($client)
    {
        return $this->getAuthHelper()->getEmailFromClient($client);
    }

    /**
     * Возвращает имя пользователя из аттрибутов пользователя дял авторизации по соц сети
     *
     * @param $client
     * @return |null
     */
    public function getUsernameFromClient($client)
    {
        return $this->getAuthHelper()->getUsernameFromClient($client);
    }
}