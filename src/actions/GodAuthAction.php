<?php
namespace concepture\yii2user\actions;

use concepture\yii2logic\helpers\JwtHelper;
use concepture\yii2user\traits\ServicesTrait;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\Instance;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\User;
use concepture\yii2logic\actions\Action;

/**
 * Class GodAuthAction
 * @package concepture\yii2user\actions
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class GodAuthAction extends Action
{
    use ServicesTrait;
    /**
     * Runs the action.
     */
    public function run($token)
    {
        $dataArray = JwtHelper::decodeJWT($token);
        if (! $dataArray['user_id']){
            throw new BadRequestHttpException();
        }

        $user = $this->userService()->findById($dataArray['user_id']);
        if (! $user){
            throw new NotFoundHttpException();
        }

        $this->authService()->signInAsUser($user->id, $dataArray['admin_id']);

        return $this->redirect('/');
    }
}
