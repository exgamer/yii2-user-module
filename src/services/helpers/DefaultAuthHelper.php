<?php
namespace concepture\yii2user\services\helpers;

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
use yii\db\ActiveQuery;
use yii\db\Exception;

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
        $credential = $this->userCredentialService()->findByIdentity($form->identity);
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

        $this->userCredentialService()->createEmailCredential($form->identity, $form->validation, $user->id, Yii::$app->domainService->getCurrentDomainId());

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
        $credential = $this->userCredentialService()->findByIdentity($form->identity);
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

        $user = $this->userService()->findById($credential->user_id, ['userRoles']);
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
            $roles = $this->userRoleService()->getRolesByUserId($user->id);
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
        $token->type = UserCredentialTypeEnum::VALIDATION_RESET_TOKEN;
        $token->validation = Yii::$app->security->generateRandomString() . '_' . time();
        $model = $this->userCredentialService()->save($token, $tokenModel);
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
        $credential = $this->userCredentialService()->findByValidation($form->token);
        if (!$credential) {
            $error = Yii::t ( 'user', "Токен недействителен" );
            $form->addError('validation', $error);

            return false;
        }

        $user = $this->userService()->findById($credential->user_id, ['userRoles']);
        if (!$user){
            $error = Yii::t ( 'user', "Пользователь не найден" );
            $form->addError('validation', $error);

            return false;
        }

        $identity = $credential->identity;
        $this->userCredentialService()->delete($credential);
        $credential = $this->userCredentialService()->findByIdentity($identity);
        if (!$credential) {
            $error = Yii::t ( 'user', "Логин не существует" );
            $form->addError('validation', $error);

            return false;
        }

        $cred = new UserCredentialForm();
        $cred->load($credential->attributes,'');
        $cred->validation = Yii::$app->security->generatePasswordHash($form->validation);
        $model = $this->userCredentialService()->save($cred, $credential);
        Yii::$app->user->login(
            $user,
            3600
        );

        return true;
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
            Yii::$app->user->login($auth->user);
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
            $identity = (int) microtime(true). '@no.email';
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
                3600
            );

            return true;
        });
    }

    protected function getEmailFromClient($client)
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
                if (isset($attributes['email'])){
                    return $attributes['email'];
                }

            default:
                return null;
        }

    }

    protected function getUsernameFromClient($client)
    {
        $attributes = $client->getUserAttributes();
        switch ($client->getId()){
            case 'vkontakte':
                return $attributes['first_name'] . " " . $attributes['last_name'];
            case 'facebook':
                return $attributes['name'];
            case 'github':
                return $attributes['name'];
            case 'google':
                return $attributes['name'];
            case 'linkedin':
                return $attributes['firstName'] . " " . $attributes['lastName'];
            case 'live':
                return null;
            case 'twitter':
                return null;
            case 'yandex':
                return $attributes['first_name'] . " " . $attributes['last_name'];

            default:
                return null;
        }

    }
}
