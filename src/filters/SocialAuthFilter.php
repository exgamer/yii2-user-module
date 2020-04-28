<?php
namespace concepture\yii2user\filters;

use concepture\yii2logic\helpers\JwtHelper;
use Yii;
use yii\base\ActionFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Class SocialAuthFilter
 * @package common\filters
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SocialAuthFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if(Yii::$app->request->getIsOptions()) {
            return true;
        }

        if (Yii::$app->request->getQueryParam('s_c')){
            $data = JwtHelper::decodeJWT(Yii::$app->request->getQueryParam('s_c'));
            $client = $data['client'];
            Yii::$app->authService->onSocialAuthSuccess($client);

            $redirect = Url::current(['s_c'=>null], true);

            $this->owner->redirect($redirect);
        }

        return parent::beforeAction($action);
    }
}
