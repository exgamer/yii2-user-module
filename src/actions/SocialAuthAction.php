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
            Yii::$app->getSession()->set(Yii::$app->user->returnUrlParam, Yii::$app->request->referrer);
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
