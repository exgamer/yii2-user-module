<?php
namespace concepture\yii2user\services\helpers;

use concepture\yii2logic\helpers\StringHelper;
use concepture\yii2user\authclients\Client;
use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\forms\ChangePasswordForm;
use concepture\yii2user\forms\CredentialConfirmForm;
use Yii;
use concepture\yii2logic\services\Service;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\EmailPasswordResetRequestForm;
use concepture\yii2user\forms\PasswordResetForm;
use concepture\yii2user\forms\SignInForm;
use concepture\yii2user\forms\SignUpForm;
use concepture\yii2user\forms\UserCredentialForm;
use concepture\yii2user\services\interfaces\AuthHelperInterface;
use concepture\yii2user\traits\ServicesTrait;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\enum\IsDeletedEnum;
use yii\authclient\BaseClient;
use yii\db\ActiveQuery;
use yii\db\Exception;
use concepture\yii2logic\helpers\MailerHelper;
use yii\helpers\Json;

/**
 * Class DomainAuthHelper
 * @package concepture\yii2user\services\helpers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class DomainAuthHelper extends DefaultAuthHelper
{

    /**
     * Регистрация пользователя
     *
     * @param SignUpForm $form
     * @return ActiveRecord|boolean
     * @throws Exception
     */
    public function signUp(SignUpForm $form)
    {
        $credential = $this->userCredentialService()->findByEmail($form->identity);
        if ($credential) {
            $error = Yii::t ( 'user', "Логин уже занят" );
            $form->addError('identity', $error);

            return false;
        }

        $user = $this->userService()->createUser($form->username);
        if (! $user){
            $error = Yii::t ( 'user', "Не удалось сохранить нового пользователя" );
            $form->addError('identity', $error);

            return false;
        }

        $realPass = $form->validation;
        $cred = $this->userCredentialService()->createEmailCredential($form->identity, $form->validation, $user->id, Yii::$app->domainService->getCurrentDomainId(), $form->status);
        $tokenModel = $this->userCredentialService()->findByIdentity($form->identity, UserCredentialTypeEnum::CREDENTIAL_CONFIRM_TOKEN);
        $token = new UserCredentialForm();
        $token->user_id = $cred->user_id;
        $token->identity = $cred->identity;
        $token->parent_id = $cred->id;
        $token->type = UserCredentialTypeEnum::CREDENTIAL_CONFIRM_TOKEN;
        $token->validation = Yii::$app->security->generateRandomString() . '_' . time();
        $model = $this->userCredentialService()->save($token, $tokenModel);
        $form->confirmToken = $token->validation;
        if ($form->sendMail) {
            MailerHelper::send(
                $form->identity,
                Yii::t('user', 'Успешная регистрация - ' . Yii::$app->name),
                Yii::$app->controller->renderPartial($form->mailTmpPath, ['form' => $form, 'password' => $realPass])
            );
        }

        return $user;
    }

    /**
     * Подтверждение учетки
     *
     * @param CredentialConfirmForm $form
     * @return bool
     */
    public function confirmCredential(CredentialConfirmForm $form)
    {
        $credential = $this->userCredentialService()->findCredentialConfirmToken($form->token);
        if (!$credential) {
            $error = Yii::t ( 'user', "Токен недействителен" );
            $form->addError('token', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id);
        if (!$user){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('token', $error);

            return false;
        }

        $identity = $credential->identity;
        $this->userCredentialService()->delete($credential);
        $credential = $this->userCredentialService()->findByIdentity($identity, UserCredentialTypeEnum::EMAIL, UserCredentialStatusEnum::INACTIVE);
        if (!$credential) {
            $error = Yii::t ( 'user', "Логин не существует" );
            $form->addError('token', $error);

            return false;
        }

        $credential->status = UserCredentialStatusEnum::ACTIVE;
        $credential->save(false);
        Yii::$app->user->login(
            $user,
            3600
        );

        return true;
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
        $credential = $this->userCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Неверный логин" );
            $form->addError('identity', $error);

            return false;
        }

        $validation = $credential->validation;
        /**
         * Если паролей много ищем для текущего домена
         */
        if (StringHelper::isJson($credential->validation)) {
            $validationArray = Json::decode($credential->validation);
            $validation = $validationArray[Yii::$app->domainService->getCurrentDomainId()] ?? null;
        }

        if(! $validation) {
            $error = Yii::t ( 'user', "Неверный пароль для текущей версии" );
            $form->addError('identity', $error);

            return false;
        }

        if (! Yii::$app->security->validatePassword($form->validation, $validation)) {
            $error = Yii::t ( 'user', "Неверный пароль" );
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id);
        if ($user->status !== StatusEnum::ACTIVE) {
            $error = Yii::t ( 'user', "Пользователь неактивен" );
            $form->addError('identity', $error);

            return false;
        }

        if ($user->is_deleted === IsDeletedEnum::DELETED) {
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('identity', $error);

            return false;
        }

        $user->last_login = date('Y-m-d H:i:s');
        $user->save(false);

        Yii::$app->user->login(
            $user,
            $form->rememberMe ? 3600 : 0
        );

        return true;
    }

    /**
     * Логаут
     *
     * @return mixed
     */
    public function signOut()
    {
        return Yii::$app->user->logout();
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
        $credential = $this->userCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Неверный логин" );
            $form->addError('identity', $error);

            return false;
        }

        $tokenModel = $this->userCredentialService()->findByIdentity($form->identity, UserCredentialTypeEnum::VALIDATION_RESET_TOKEN);
        $token = new UserCredentialForm();
        $token->user_id = $credential->user_id;
        $token->identity = $credential->identity;
        $token->parent_id = $credential->id;
        if ($credential->domain_id) {
            $token->domain_id = Yii::$app->domainService->getCurrentDomainId();
        }

        $token->type = UserCredentialTypeEnum::VALIDATION_RESET_TOKEN;
        $token->validation = Yii::$app->security->generateRandomString() . '_' . time();
        $model = $this->userCredentialService()->save($token, $tokenModel);
        $form->token = $token->validation;
        if ($form->sendMail) {
            MailerHelper::send(
                $form->identity,
                Yii::t('user', 'Смена пароля - ' . Yii::$app->name),
                Yii::$app->controller->renderPartial($form->mailTmpPath, ['route' => $form->route, 'token' => $model->validation])
            );
        }

        return true;
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
        $credential = $this->userCredentialService()->findValidationResetToken($form->token);
        if (! $credential) {
            $error = Yii::t ( 'user', "Токен недействителен" );
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id);
        if (!$user){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('validation', $error);

            return false;
        }

        $identity = $credential->identity;
        $this->userCredentialService()->delete($credential);
        $credential = $this->userCredentialService()->findByIdentity($identity);
        if (! $credential) {
            $error = Yii::t ( 'user', "Логин не существует" );
            $form->addError('validation', $error);

            return false;
        }

        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = $this->resolveValidation($credential, $form->validation);
        $model = $this->userCredentialService()->save($cred, $credential);
        Yii::$app->user->login(
            $user,
            3600
        );

        return true;
    }

    /**
     * смена пароля
     *
     * @param ChangePasswordForm $form
     */
    public function changePassword(ChangePasswordForm $form)
    {
        $credential = $this->userCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Логин не существует" );
            $form->addError('validation', $error);

            return false;
        }

        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = $this->resolveValidation($credential, $form->new_password);
        $model = $this->userCredentialService()->save($cred, $credential);
        if ($model) {
            return true;
        }

        return false;
    }

    /**
     * Мапим пароль в учетке
     *
     * @param $credential
     * @param $password
     * @return array|mixed|null
     * @throws \yii\base\Exception
     */
    public function resolveValidation($credential, $password)
    {
        /**
         * Проверка идет ли сброс пароля для домена
         */
        $newValidation = Yii::$app->security->generatePasswordHash($password);
        $validation = $credential->validation;
        if ($credential->domain_id !== null) {
            if (StringHelper::isJson($credential->validation)) {
                $validation = Json::decode($credential->validation);
            }
        }

        if ($credential->domain_id > 0 && ($credential->domain_id != Yii::$app->domainService->getCurrentDomainId())) {
            if (is_array($validation)) {
                $validation[Yii::$app->domainService->getCurrentDomainId()] = $newValidation;
            }else{
                $validationArray = [];
                $validationArray[$credential->domain_id] = $validation;
                $validationArray[Yii::$app->domainService->getCurrentDomainId()] = $newValidation;
                $validation = $validationArray;
            }
        }

        if (is_array($validation)) {
            return Json::encode($validation);
        }

        return $validation;
    }
}
