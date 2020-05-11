<?php
namespace concepture\yii2user;

use Yii;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * Класс описывающий атворизованного юзера
 *
 * Class WebUser
 * @package concepture\yii2user
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class WebUser extends User
{
    /**
     * Префикс для ключа в кеше для признака онлайн пользователь
     *
     * @var string
     */
    public static $isActivePrefix = 'user-online-';

    /**
     * @inheritDoc
     */
    protected function renewAuthStatus()
    {
        parent::renewAuthStatus();
        $session = Yii::$app->getSession();
        $identity = $this->getIdentity();
        if ($identity  && Yii::$app->has('cache')) {
            Yii::$app->cache->getOrSet(static::$isActivePrefix . $identity->id, function () {
                return 1;
            }, 180);
        }
    }
}
