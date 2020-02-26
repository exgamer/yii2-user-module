<?php
namespace concepture\yii2user\helpers;

use concepture\yii2logic\helpers\JwtHelper;
use Yii;
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
        $host = Url::home(YII_DEBUG?'http':'https');

        return Yii::$app->params['SSO_HOST']. "/api/auth/sign-in?redirect={$host}";
    }

    public static function getCheckoutUrl()
    {
        return Yii::$app->params['SSO_HOST']. "/checkout?token=" . static::getSsoJwtToken();
    }

    public static function getSsoJwtToken()
    {
        $payload = [
            'app_id' => Yii::$app->params['SSO_APP_ID']
        ];
        if (! Yii::$app->user->isGuest){
            $payload['user_id'] = Yii::$app->user->identity->id;
        }

        return JwtHelper::getJWT($payload);
    }
}