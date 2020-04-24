<?php
namespace concepture\yii2user\filters;

use concepture\yii2logic\helpers\JwtHelper;
use Yii;
use yii\base\ActionFilter;
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

        if (Yii::$app->request->getQueryParam('socialclient')){
            $data = JwtHelper::decodeJWT(Yii::$app->request->getQueryParam('socialclient'));
            $client = unserialize($data['client']);
            Yii::$app->authService->onSocialAuthSuccess($client);

            $this->owner->refresh();
        }
        
        return true;
    }
}
