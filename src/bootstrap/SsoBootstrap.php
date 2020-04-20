<?php
namespace concepture\yii2user\bootstrap;

use Yii;
use yii\base\Event;
use yii\helpers\Html;
use yii\base\Application;
use yii\base\BootstrapInterface;
use concepture\yii2logic\services\Service;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\enum\ServiceEventEnum;
use kamaelkz\yii2admin\v1\widgets\formelements\activeform\ActiveForm;
use concepture\yii2logic\enum\IsDeletedEnum;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\helpers\JwtHelper;
use concepture\yii2user\helpers\SsoHelper;
use yii\web\UrlNormalizer;

/**
 * Class SsoBootstrap
 * @package backend\bootstrap
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SsoBootstrap implements BootstrapInterface
{
    public $checkAlways = true;

    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        if (! SsoHelper::isSsoEnabled()){
            return;
        }

        Event::on(Application::class, Application::EVENT_BEFORE_REQUEST, function($event) {
            $app = $event->sender;
            $request = $app->getRequest();
            $sso = $request->getQueryParam('sso');
            if (Yii::$app->user->isGuest ) {
                $ssoCockie = Yii::$app->authService->getAuthHelper()->getSsoCookie();
                if ($this->checkAlways){
                    $ssoCockie = false;
                }
                if (! $sso && ! $ssoCockie) {
                    Yii::$app->authService->getAuthHelper()->setSsoCookie();
                    $response = $app->getResponse();
                    $response->redirect(SsoHelper::getCheckoutUrl(), UrlNormalizer::ACTION_REDIRECT_PERMANENT);
                }
                if ($sso){
                    $data = JwtHelper::decodeJWT($sso);
                    if (isset($data['user_id']) && $data['user_id'] !== null){
                        $user = Yii::$app->userService->findById($data['user_id']);
                        if ($user->status !== StatusEnum::ACTIVE){
                            return false;
                        }

                        if ($user->is_deleted === IsDeletedEnum::DELETED){
                            return false;
                        }

                        Yii::$app->user->login(
                            $user,
                            3600
                        );
                    }
                }
            }
        });
    }
}