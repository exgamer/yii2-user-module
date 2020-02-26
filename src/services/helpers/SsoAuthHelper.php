<?php
namespace concepture\yii2user\services\helpers;

use concepture\yii2logic\enum\IsDeletedEnum;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\helpers\JwtHelper;
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


/**
 * Class SsoAuthHelper
 * @package concepture\yii2user\services\helpers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SsoAuthHelper implements AuthHelperInterface
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
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 422) {
                $errors = json_decode($e->getResponse()->getBody()->getContents(), true);
                if (isset($errors[0])){
                    $form->addErrors($errors[0]);
                }
            }else{
                throw new \Exception($e->getMessage());
            }
        }

        return;


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
                SsoHelper::getSignInUrl('site/index'),
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
        $user = $this->userService()->findById($userId, ['userRoles']);
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

    }
}
