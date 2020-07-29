<?php
namespace concepture\yii2user\services\helpers;

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

/**
 * Class DefaultAuthHelper
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class DefaultAuthHelper implements AuthHelperInterface
{
    use ServicesTrait;

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
            $error = Yii::t('general', "Email is already in use");
            $form->addError('identity', $error);

            return false;
        }

        $user = $this->userService()->createUser($form->username);
        if (! $user){
            $error = Yii::t('general', "Failed to save new user");
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
                Yii::t('general', 'Successful registration - {app_name}', [
                    ':app_name' => Yii::$app->name,
                ]),
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
        $credential = $this->userCredentialService()->findByValidation($form->token);
        if (!$credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Token is invalid");
            $form->addError('token', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id);
        if (!$user){
            $error = Yii::t('general', "User is not found");
            $form->addError('token', $error);

            return false;
        }

        $identity = $credential->identity;
        $this->userCredentialService()->delete($credential);
        $credential = $this->userCredentialService()->findByIdentity($identity, UserCredentialTypeEnum::EMAIL, UserCredentialStatusEnum::INACTIVE);
        if (!$credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Login does not exist");
            $form->addError('token', $error);

            return false;
        }

        $credential->status = UserCredentialStatusEnum::ACTIVE;
        $credential->save(false);
        Yii::$app->user->login(
            $user,
            60*60*24*365
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
        $credential = $this->userCredentialService()->findByEmail($form->identity);
        if (!$credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Invalid login");
            $form->addError('identity', $error);

            return false;
        }

        if ($credential->status !== UserCredentialStatusEnum::ACTIVE) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Credential is inactive");
            $form->addError('identity', $error);

            return false;
        }

        if (! Yii::$app->security->validatePassword($form->validation, $credential->validation)) {
            $error = Yii::t('general', "Wrong password");
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id);
        if ($user->status !== StatusEnum::ACTIVE) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("User is inactive");
            $form->addError('identity', $error);

            return false;
        }

        if ($user->is_deleted === IsDeletedEnum::DELETED) {
            $error = Yii::t('general', "User is not found");
            $form->addError('identity', $error);

            return false;
        }

        /**
         * Если у формы установлен признак onlyWithAuthAssignment
         * проверяем что у юзера есть хоть одна роль
         */
        if ($form->onlyWithAuthAssignment === true) {
            if (! $this->rbacService()->hasRoleAssignment($user->id)) {
                $error = Yii::t('general', "Access denied");
                $form->addError('identity', $error);

                return false;
            }
        }

        $user->last_login = date('Y-m-d H:i:s');
        $user->save(false);

        Yii::$app->user->login(
            $user,
            $form->rememberMe ? (60*60*24*365) : 0
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
        $credential = $this->userCredentialService()->findByEmail($form->identity);
        if (!$credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Invalid login");
            $form->addError('identity', $error);

            return false;
        }

        if ($credential->status !== UserCredentialStatusEnum::ACTIVE) {
            $error = Yii::t('general', "Credential is inactive");
            $form->addError('identity', $error);

            return false;
        }

        $tokenModel = $this->userCredentialService()->findByIdentity($form->identity, UserCredentialTypeEnum::VALIDATION_RESET_TOKEN);
        $token = new UserCredentialForm();
        $token->user_id = $credential->user_id;
        $token->identity = $credential->identity;
        $token->parent_id = $credential->id;
        $token->type = UserCredentialTypeEnum::VALIDATION_RESET_TOKEN;
        $token->validation = Yii::$app->security->generateRandomString() . '_' . time();
        $model = $this->userCredentialService()->save($token, $tokenModel);
        $form->token = $token->validation;
        if ($form->sendMail) {
            MailerHelper::send(
                $form->identity,
                Yii::t('general', 'Password change - {app_name}', [
                    ':app_name' => Yii::$app->name,
                ]),
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
        $credential = $this->userCredentialService()->findByValidation($form->token);
        if (!$credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Token is invalid");
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id);
        if (!$user){
            $error = Yii::t('general', "User is not found");
            $form->addError('validation', $error);

            return false;
        }

        $identity = $credential->identity;
        $this->userCredentialService()->delete($credential);
        $credential = $this->userCredentialService()->findByIdentity($identity);
        if (!$credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Login does not exist");
            $form->addError('validation', $error);

            return false;
        }

        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = Yii::$app->security->generatePasswordHash($form->validation);
        $model = $this->userCredentialService()->save($cred, $credential);
        Yii::$app->user->login(
            $user,
            60*60*24*365
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
        if (! $credential) {
            $error = Yii::t('general', "Account not found");
            Yii::warning("Login does not exist");
            $form->addError('validation', $error);

            return false;
        }

        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = Yii::$app->security->generatePasswordHash($form->new_password);
        $model = $this->userCredentialService()->save($cred, $credential);
        if ($model) {
            return true;
        }

        return false;
    }

    /**
     * Метод для вызова в случае успешной авторизации по соц сети
     *
     *Пример
     *
     *     public function actions()
     *       {
     *           return [
     *           'auth' => [
     *                   'class' => 'yii\authclient\AuthAction',
     *                   'successCallback' => [$this, 'onAuthSuccess'],
     *               ],
     *           ];
     *       }
     *
     *       public function onAuthSuccess($client)
     *       {
     *            Yii::$app->authService->onSocialAuthSuccess($client);
     *       }
     *
     *
     *
     * @param $client
     * @return bool
     */
    public function onSocialAuthSuccess($client)
    {
        /**
         * хак для возможности авторизации по массиву
         */
        if (! $client instanceof BaseClient){
            if (is_object($client)){
                $client = get_object_vars($client);
            }

            $newClient = new Client();
            $newClient->setId($client['id']);
            $newClient->setName($client['name']);
            $newClient->setTitle($client['title']);
            if (is_object($client['userAttributes'])){
                $userAttributes = get_object_vars($client['userAttributes']);
            }else{
                $userAttributes = $client['userAttributes'];
            }

            $newClient->setUserAttributes($userAttributes);
            $client = $newClient;
        }

        $attributes = $client->getUserAttributes();
        $auth = $this->userSocialAuthService()->getOneByCondition(function(ActiveQuery $query) use($client, $attributes){
            $query->andWhere([
                'source_id' =>  $client->getId(),
                'source_user_id' =>  $attributes['id'],
            ]);
            $query->with('user');
        });

        if (! Yii::$app->user->isGuest && ! $auth){
            $this->userSocialAuthService()->createByClient($client, Yii::$app->user->identity->id);
            return true;
        }

        if (! Yii::$app->user->isGuest ){
            return true;
        }

        if ($auth) { // авторизация
            Yii::$app->user->login($auth->user, 3600);

            return true;
        }

        $identity = null;
        $email = $this->getEmailFromClient($client);
        if ($email){
            $identity = $email;
            $existCredential = $this->userCredentialService()->findByIdentity($identity);
            /**
             * @TODO если емаил уже есть ниче не делаем и тут надо решить выбивать ли исключение ил просто реутрн фолс оставить
             */
            if (! empty($existCredential)){

                return false;
            }

        }

        /**
         * Если емеила нет генерим логин по микротаим
         * чтобы отработала регистрация
         */
        if (! $identity){
            $identity = Yii::$app->security->generateRandomString(6) .(int) microtime(true). '@no.email';
        }

        $model = new SignUpForm();
        $model->identity = $identity;
        $model->validation = Yii::$app->security->generateRandomString(6);
        $username = $this->getUsernameFromClient($client);
        if (! $username){
            $username = $identity;
        }

        $model->username = $username;
        $this->userService()->getDb()->transaction(function($db) use ($model, $client){
            $user = $this->authService()->signUp($model);
            if (! $user) {
                throw new Exception();
            }

            if (! $this->userSocialAuthService()->createByClient($client, $user->id)){
                throw new Exception();
            }

            Yii::$app->user->login(
                $user,
                60*60*24*365
            );

            return true;
        });
    }

    /**
     * Возвращает email из аттрибутов пользователя дял авторизации по соц сети
     *
     * @param $client
     * @return |null
     */
    public function getEmailFromClient($client)
    {
        $attributes = $client->getUserAttributes();
        switch ($client->getId()){
            case 'yandex':
                if (isset($attributes['default_email'])){
                    return $attributes['default_email'];
                }
            case 'vkontakte':
            case 'facebook':
            case 'github':
            case 'google':
            case 'linkedin':
            case 'live':
            case 'twitter':
            case 'mailru':
            case 'instagram':
            case 'odnoklassniki':
                if (isset($attributes['email'])){
                    return $attributes['email'];
                }

            default:
                return null;
        }

    }

    /**
     * Возвращает имя пользователя из аттрибутов пользователя дял авторизации по соц сети
     *
     * @param $client
     * @return |null
     */
    public function getUsernameFromClient($client)
    {
        $attributes = $client->getUserAttributes();
        switch ($client->getId()){
            case 'vkontakte':
            case 'yandex':
            case 'odnoklassniki':
                return $attributes['first_name'] . " " . $attributes['last_name'];
            case 'instagram':
                if (isset($attributes['username'])){
                    return $attributes['username'];
                }

                if (isset($attributes['name'])){
                    return $attributes['name'];
                }
            case 'mailru':
                return $attributes['nick'];
            case 'facebook':
            case 'github':
            case 'google':
                return $attributes['name'];
            case 'linkedin':
                return $attributes['firstName'] . " " . $attributes['lastName'];
            case 'live':
            case 'twitter':
                return null;
        }
    }
}
