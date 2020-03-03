<?php
namespace concepture\yii2user\actions;

use yii\authclient\AuthAction;
use Yii;

/**
 * Класс  переопределен чтобы дать возможность редиректа на эту же страницу после авторизации
 *
 * Class SocialAuthAction
 * @package concepture\yii2user\actions
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SocialAuthAction extends AuthAction
{
    /**
     * @return mixed
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

            Yii::$app->getSession()->set(Yii::$app->user->returnUrlParam, $redirect);
        }

        return parent::run();
    }

    /**
     * @return mixed
     */
    protected function defaultSuccessUrl()
    {
        $url = $this->user->getReturnUrl();
        Yii::$app->getSession()->remove(Yii::$app->user->returnUrlParam);

        return $url;
    }

}
