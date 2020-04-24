<?php
namespace concepture\yii2user\actions;

use concepture\yii2logic\actions\Action;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Авторизация по соц сети на стороннем сервере
 *
 * Class RemoteSocialAuthAction
 * @package concepture\yii2user\actions
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class RemoteSocialAuthAction extends Action
{
    public function run()
    {
        if (! isset(Yii::$app->request->post()['client'])){
            throw new BadRequestHttpException();
        }

        $client = unserialize(Yii::$app->request->post()['client']);
        Yii::$app->authService->onSocialAuthSuccess($client);

        return $this->responseJson([]);
    }
}