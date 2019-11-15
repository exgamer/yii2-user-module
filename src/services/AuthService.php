<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\helpers\MailerHelper;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\EmailPasswordResetRequestForm;
use concepture\yii2user\forms\PasswordResetForm;
use concepture\yii2user\forms\SignInForm;
use concepture\yii2user\forms\UserCredentialForm;
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
class AuthService extends Service
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
        $credential = $this->getUserCredentialService()->findByIdentity($form->identity);
        if ($credential) {
            $error = Yii::t ( 'user', "Логин уже занят" );
            $form->addError('identity', $error);

            return false;
        }

        $user = $this->getUserService()->createUser($form->username);
        $this->getUserCredentialService()->createEmailCredential($form->identity, $form->validation, $user->id);

        return $user;
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
        $credential = $this->getUserCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Неверный логин" );
            $form->addError('identity', $error);

            return false;
        }

        if (!Yii::$app->security->validatePassword($form->validation, $credential->validation)){
            $error = Yii::t ( 'user', "Неверный пароль" );
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->getUserService()->findById($credential->user_id, ['roles']);
        if (!Yii::$app->security->validatePassword($form->validation, $credential->validation)){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('identity', $error);

            return false;
        }

        if ($user->status !== StatusEnum::ACTIVE){
            $error = Yii::t ( 'user', "Пользователь неактивен" );
            $form->addError('identity', $error);

            return false;
        }

        if ($user->is_deleted === IsDeletedEnum::DELETED){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('identity', $error);

            return false;
        }

        if (!empty($form->restrictions)){
            $roles = Yii::$app->userRoleService->getRolesByUserId($user->id);
            $roles = array_keys($roles);
            $result = array_intersect ($roles, $form->restrictions);
            if (empty($result)){
                $error = Yii::t ( 'user', "Пользователь не найден" );
                $form->addError('identity', $error);

                return false;
            }
        }

        Yii::$app->user->login(
            $user,
            $form->rememberMe ? 3600 : 0
        );

        return true;
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
        $credential = $this->getUserCredentialService()->findByIdentity($form->identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Неверный логин" );
            $form->addError('identity', $error);

            return false;
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
            Yii::$app->controller->renderPartial("@concepture/yii2user/views/mailer/password_reset_html",['route'=>$form->route, 'token'=>$model->validation])
        );

        return true;
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
        $credential = $this->getUserCredentialService()->findByValidation($form->token);
        if (!$credential) {
            $error = Yii::t ( 'user', "Токен недействителен" );
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->getUserService()->findById($credential->user_id, ['roles']);
        if (!$user){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('validation', $error);

            return false;
        }

        $identity = $credential->identity;
        $this->getUserCredentialService()->delete($credential);
        $credential = $this->getUserCredentialService()->findByIdentity($identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Логин не существует" );
            $form->addError('validation', $error);

            return false;
        }

        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = $form->validation;
        $model = $this->getUserCredentialService()->save($cred, $credential);
        Yii::$app->user->login(
            $user,
            3600
        );

        return true;
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