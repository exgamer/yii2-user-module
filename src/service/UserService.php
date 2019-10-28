<?php
namespace concepture\yii2user\service;

use concepture\yii2user\forms\UserForm;
use concepture\yii2logic\services\Service;

/**
 * UserService
 *
 */
class UserService extends Service
{
    public function createUser($username)
    {
        $form = new UserForm();
        $form->username = $username;

        return $this->create($form);
    }
}
