<?php
namespace concepture\yii2user\services\helpers;

use concepture\yii2logic\enum\IsDeletedEnum;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\helpers\JwtHelper;
use concepture\yii2user\forms\ChangePasswordForm;
use concepture\yii2user\forms\CredentialConfirmForm;
use concepture\yii2user\helpers\SsoHelper;
use GuzzleHttp\Client;
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
use yii\helpers\Url;
use yii\web\Cookie;


/**
 * Class SsoAuthHelper
 * @package concepture\yii2user\services\helpers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SsoAuthHelper implements AuthHelperInterface
{
    use ServicesTrait;

    /**
     * @TODO нереализовано продтверждение учетки
     * Регистрация пользователя
     *
     * @param SignUpForm $form
     * @return ActiveRecord|boolean
     * @throws Exception
     */
    public function signUp(SignUpForm $form)
    {
        $options = [];
        $client = new Client([
            'timeout'=> 0
        ]);
        $options['headers'] = ['X-DATA' => SsoHelper::getSsoJwtToken()];
        $options['query'] = ['identity' => $form->identity];
        try{
            $response = $client->request(
                'GET',
                SsoHelper::getIdentityExistenceCheckUrl(),
                $options
            );
            $error = Yii::t('common', "Логин уже занят" );
            $form->addError('identity', $error);
        } catch (\GuzzleHttp\Exception\RequestException $e) {}

        $user = $this->userService()->createUser($form->username);
        if (! $user){
            $error = Yii::t('common', "Не удалось сохранить нового пользователя" );
            $form->addError('identity', $error);

            return false;
        }

        $options = [];
        $client = new Client([
            'timeout'=> 0
        ]);
        $options['headers'] = ['X-DATA' => SsoHelper::getSsoJwtToken(['user_id' => $user->id])];
        $options['form_params'] = [
            'identity' => $form->identity,
            'validation' => $form->validation,
        ];
        try{
            $response = $client->request(
                'POST',
                SsoHelper::getSignUpUrl(),
                $options
            );
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 422) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (isset($errors[0])){
                    $form->addErrors($errors[0]);
                }

                return false;
            }else{
                throw new \Exception($e->getMessage());
            }
        }

        return $user;
    }

    public function confirmCredential(CredentialConfirmForm $form)
    {
        throw new \Exception("not realised!!!");
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
        $options = [];
        $client = new Client([
            'timeout'=> 0
        ]);
        $options['headers'] = ['X-DATA' => SsoHelper::getSsoJwtToken()];
        $options['form_params'] = [
            'identity' => $form->identity,
            'validation' => $form->validation,
        ];
        try{
            $response = $client->request(
                'POST',
                SsoHelper::getSignInUrl(),
                $options
            );
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 422) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (isset($errors[0])){
                    $form->addErrors($errors[0]);
                }

                return false;
            }else{
                throw new \Exception($e->getMessage());
            }
        }

        $data = JwtHelper::decodeJWT($body['token']);
        $userId = $data['user_id'];
        $user = $this->userService()->findById($userId);
        if ($user->status !== StatusEnum::ACTIVE){
            $error = Yii::t('common', "Пользователь неактивен" );
            $form->addError('identity', $error);

            return false;
        }

        if ($user->is_deleted === IsDeletedEnum::DELETED){
            $error = Yii::t('common', "Пользователь не найден" );
            $form->addError('identity', $error);

            return false;
        }

        Yii::$app->user->login(
            $user,
            $form->rememberMe ? 3600 : 0
        );

        return $body;
    }

    /**
     * Логаут
     *
     * @return mixed
     */
    public function signOut()
    {
        if (Yii::$app->user->isGuest){
            return true;
        }

        $this->removeSsoCookie();

        Yii::$app->user->logout();

        return [
            'redirect' => SsoHelper::getLogoutUrl()
        ];
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
        $options = [];
        $client = new Client([
            'timeout'=> 0
        ]);
        $options['headers'] = ['X-DATA' => SsoHelper::getSsoJwtToken()];
        $options['form_params'] = [
            'identity' => $form->identity,
            'route' => Url::to($form->route, true),
        ];
        try{
            $response = $client->request(
                'POST',
                SsoHelper::getRequestPasswordResetUrl(),
                $options
            );
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 422) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (isset($errors[0])){
                    $form->addErrors($errors[0]);
                }

                return false;
            }else{
                throw new \Exception($e->getMessage());
            }
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
        $options = [];
        $client = new Client([
            'timeout'=> 0
        ]);
        $options['headers'] = ['X-DATA' => SsoHelper::getSsoJwtToken()];
        $options['form_params'] = [
            'validation' => $form->validation,
        ];
        try{
            $response = $client->request(
                'POST',
                SsoHelper::getResetPasswordUrl($form->token),
                $options
            );
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 422) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (isset($errors[0])){
                    $form->addErrors($errors[0]);
                }

                return false;
            }else{
                throw new \Exception($e->getMessage());
            }
        }

        return true;
    }

    /**
     * смена пароля
     *
     * @param ChangePasswordForm $form
     */
    public function changePassword(ChangePasswordForm $form)
    {
        // TODO: Implement changePassword() method.
    }

    /**
     * Установка куки sso
     *
     * @param string $alias
     */
    public function setSsoCookie()
    {
        $cookies = Yii::$app->getResponse()->cookies;
        $cookies->add(new Cookie([
            'name' => 'sso_checked',
            'value' => true,
            'expire' => time() + 60,
        ]));
    }

    /**
     * Получение куки sso
     *
     * @return mixed
     */
    public function getSsoCookie()
    {
        $cookies = Yii::$app->getRequest()->cookies;

        return $cookies->getValue('sso_checked');
    }

    /**
     * удаление куки sso
     *
     * @return mixed
     */
    public function removeSsoCookie()
    {
        $cookies = Yii::$app->response->cookies;

        return $cookies->remove('sso_checked');
    }

    public function onSocialAuthSuccess($client)
    {
        // @TODO реализовать авторизацию через социалки для sso
    }
}