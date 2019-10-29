<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\helpers\MailerHelper;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\EmailPasswordResetRequestForm;
use concepture\yii2user\forms\PasswordResetForm;
use concepture\yii2user\forms\SignInForm;
use concepture\yii2user\forms\UserCredentialForm;
use Yii;
use concepture\yii2user\forms\SignUpForm;
use concepture\yii2logic\services\Service;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * AuthService
 *
 */
class AuthService extends Service
{
    /**
     * Регистрация пользователя
     *
     * @param SignUpForm $form
     * @throws \Exception
     */
    public function signUp(SignUpForm $form)
    {
        $credential = $this->getUserCredentialService()->findByIdentity($form->identity);
        if ($credential) {
            $error = Yii::t ( 'user', "Логин уже занят" );
            $form->addError('identity', $error);
            throw new \Exception($error);
        }
        $user = $this->getUserService()->createUser($form->username);
        $this->getUserCredentialService()->createEmailCredential($form->identity, $form->validation, $user->id);

        return $user;
    }
    /**
     * Авторизация пользователя
     *
     * @param SignInForm $form
     * @throws \Exception
     */
    public function signIn(SignInForm $form)
    {
        $credential = $this->getUserCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Неверный логин" );
            $form->addError('identity', $error);
            throw new \Exception($error );
        }
        if (!Yii::$app->security->validatePassword($form->validation, $credential->validation)){
            $error = Yii::t ( 'user', "Неверный пароль" );
            $form->addError('validation', $error);
            throw new \Exception($error);
        }
        $user = $this->getUserService()->findById($credential->user_id, ['roles']);
        if (!Yii::$app->security->validatePassword($form->validation, $credential->validation)){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('identity', $error);
            throw new \Exception($error );
        }
        if (!empty($form->restrictions)){
            $roles = Yii::$app->userRoleService->getRolesByUserId($user->id);
            $roles = array_keys($roles);
            $result = array_intersect ($roles, $form->restrictions);
            if (empty($result)){
                $error = Yii::t ( 'user', "Пользователь не найден" );
                $form->addError('identity', $error);
                throw new \Exception($error );
            }
        }
        Yii::$app->user->login(
            $user,
            $form->rememberMe ? 3600 : 0
        );
    }
    public function sendPasswordResetEmail(EmailPasswordResetRequestForm $form)
    {
        $credential = $this->getUserCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Неверный логин" );
            $form->addError('identity', $error);
            throw new \Exception($error );
        }
        $tokenModel = $this->getUserCredentialService()->findByIdentity($form->identity, UserCredentialTypeEnum::VALIDATION_RESET_TOKEN);
        $token = new UserCredentialForm();
        $token->user_id = $credential->user_id;
        $token->identity = $credential->identity;
        $token->parent_id = $credential->id;
        $token->type = UserCredentialTypeEnum::VALIDATION_RESET_TOKEN;
        $token->validation = Yii::$app->security->generateRandomString() . '_' . time();
        $model = $this->getUserCredentialService()->save($token, $tokenModel);
        MailerHelper::send(
            $form->identity,
            Yii::t('user','Смена пароля - ' . Yii::$app->name),
            Yii::$app->controller->renderPartial("@concepture/user/views/mailer/password_reset_html",['route'=>$form->route, 'token'=>$model->validation])
        );
    }
    public function changePassword(PasswordResetForm $form)
    {
        $credential = $this->getUserCredentialService()->findByValidation($form->token);
        if (!$credential) {
            $error = Yii::t ( 'user', "Токен недействителен" );
            $form->addError('token', $error);
            throw new \Exception($error );
        }
        $user = $this->getUserService()->findById($credential->user_id, ['roles']);
        if (!$user){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('token', $error);
            throw new \Exception($error );
        }
        $identity = $credential->identity;
        $this->getUserCredentialService()->delete($credential);
        $credential = $this->getUserCredentialService()->findByIdentity($identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Логин не существует" );
            $form->addError('token', $error);
            throw new \Exception($error );
        }
        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = $form->validation;
        $model = $this->getUserCredentialService()->save($cred, $credential);
        $cred->action($credential);
        Yii::$app->user->login(
            $user,
            3600
        );
    }
    /**
     * @return UserService
     */
    public function getUserService()
    {
        return Yii::$app->userService;
    }
    /**
     * @return UserCredentialService
     */
    public function getUserCredentialService()
    {
        return Yii::$app->userCredentialService;
    }
}