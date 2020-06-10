<?php
namespace concepture\yii2user\services\traits;

use Yii;
use yii\base\Model;

/**
 * Trait UserSupportTrait
 * @package concepture\yii2user\services\traits
 */
trait UserSupportTrait
{
    /**
     * Устанавливает текущего пользователя
     * @param Model $model
     * @param bool $ignoreSetted
     */
    protected function setCurrentUser(Model $model, $ignoreSetted = true)
    {
        if (! $ignoreSetted){
            return;
        }

        if ($model->user_id){
            return;
        }

        if (! Yii::$app->has('user')){
            $model->user_id = 1;

            return;
        }

        $model->user_id = Yii::$app->user->identity->id;
    }
}

