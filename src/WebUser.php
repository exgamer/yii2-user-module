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
        $identity = $this->getIdentity();
        if ($identity  && Yii::$app->has('cache')) {
            Yii::$app->cache->getOrSet(static::$isActivePrefix . $identity->id, function () use ($identity) {
                $identity->updateAttributes(['last_seen' => date('Y-m-d H:i:s')]);

                return 1;
            }, 300);
        }
    }

    public function isCurrentUser($user)
    {
        $identity = $this->getIdentity();
        if(! $identity) {

            return false;
        }

        if (! $user) {
            $user = $identity;
        }

        if (filter_var($user, FILTER_VALIDATE_INT) !== false) {
            if ($identity->id == $user) {
                return true;
            }

            return false;
        }

        if (  $identity->id == $user->id) {
            return true;
        }

        return false;
    }
}
