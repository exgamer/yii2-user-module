<?php
namespace concepture\yii2user\helpers;

use concepture\yii2logic\helpers\JwtHelper;
use concepture\yii2user\bootstrap\SsoBootstrap;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class SsoHelper
 * @package common\helpers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SsoHelper
{
    public static function isSsoEnabled()
    {
        return isset(Yii::$app->params['SSO_HOST']) && isset(Yii::$app->params['SSO_APP_ID']);
    }

    public static function getSignInUrl()
    {
        $host = Url::to('', true);

        return Yii::$app->params['SSO_HOST']. "/api/auth/sign-in?redirect={$host}";
    }

    public static function getSignUpUrl()
    {
        $host = Url::to('', true);

        return Yii::$app->params['SSO_HOST']. "/api/auth/sign-up?redirect={$host}";
    }

    public static function getIdentityExistenceCheckUrl()
    {
        return Yii::$app->params['SSO_HOST']. "/api/auth/is-identity-exists";
    }

    public static function getCheckoutUrl()
    {
        return Yii::$app->params['SSO_HOST']. "/checkout?token=" . static::getSsoJwtToken();
    }

    public static function getRequestPasswordResetUrl()
    {
        return Yii::$app->params['SSO_HOST']. "/api/auth/request-password-reset";
    }

    public static function getResetPasswordUrl($token)
    {
        return Yii::$app->params['SSO_HOST']. "/api/auth/reset-password?token" . $token;
    }

    public static function getLogoutUrl()
    {
        return Yii::$app->params['SSO_HOST']. "/logout?token=" . static::getSsoJwtToken();
    }

    public static function getSsoJwtToken($data = [])
    {
        $payload = [
            'app_id' => Yii::$app->params['SSO_APP_ID'],
            'redirect' => Url::to('', true)
        ];
        if (! Yii::$app->user->isGuest){
            $payload['user_id'] = Yii::$app->user->identity->id;
        }

        $payload = ArrayHelper::merge($payload, $data);

        return JwtHelper::getJWT($payload);
    }
}