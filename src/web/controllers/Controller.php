<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2logic\controllers\web\Controller as Base;

/**
 * Базовый контроллер для модуля пользователя
 *
 * Class Controller
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
abstract class Controller extends Base
{
    protected function getAccessRules()
    {
        return [
            [
                'actions' => ['index', 'create','update', 'view','delete'],
                'allow' => true,
                'roles' => [UserRoleEnum::ADMIN],
            ]
        ];
    }
}
