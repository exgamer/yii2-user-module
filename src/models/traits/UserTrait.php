<?php
namespace concepture\yii2user\models\traits;

use concepture\yii2user\models\User;

/**
 * Trait UserTrait
 * @package concepture\yii2user\models\traits
 */
trait UserTrait
{
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getUserName()
    {
        if (isset($this->user)){
            return $this->user->username;
        }

        return null;
    }

    public function getUserLogo()
    {
        if (isset($this->user)){
            return $this->user->getImageAttribute('logo');
        }

        return null;
    }

    public function getUserDescription()
    {
        if (isset($this->user)){
            return $this->user->description;
        }

        return null;
    }

    public function getUserWebsite()
    {
        if (isset($this->user)){
            return $this->user->website;
        }

        return null;
    }
}

