<?php
namespace concepture\yii2user\services;

use concepture\yii2user\forms\UserForm;
use concepture\yii2logic\services\Service;

/**
 * Сервис содержит бизнес логику для работы с пользователем
 *
 * Class UserService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
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
