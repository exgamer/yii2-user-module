<?php
namespace concepture\yii2user\actions;

use yii\authclient\AuthAction;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\User;

/**
 * Class SocialAuthAction
 * @package concepture\yii2user\actions
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SocialAuthAction extends AuthAction
{
    /**
     * Runs the action.
     */
    public function run()
    {
        if (Yii::$app->request->referrer) {
            $redirect = Yii::$app->request->referrer;
            $query = parse_url($redirect, PHP_URL_QUERY);
            if ($query) {
                $redirect .= '&#social-anchor';
            } else {
                $redirect .= '?#social-anchor';
            }

            $redirect = str_replace('??', '?', $redirect);
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'social_auth_redirect',
                'value' => $redirect
            ]));
        }

        return parent::run();
    }

    protected function defaultSuccessUrl()
    {
        if (Yii::$app->request->cookies->has('social_auth_redirect')){

            $url =  Yii::$app->request->cookies->getValue('social_auth_redirect');
            Yii::$app->response->cookies->remove('social_auth_redirect');

            return $url;
        }

        return parent::defaultSuccessUrl();
    }

}
