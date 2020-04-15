<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\enum\UserRoleEnum;
use kamaelkz\yii2admin\v1\controllers\BaseController;

/**
 * Базовый контроллер для модуля пользователя
 *
 * Class Controller
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
abstract class Controller extends BaseController
{
//    protected function getAccessRules()
//    {
//        return [
//            [
//                'actions' => ['index', 'create','update', 'view','delete'],
//                'allow' => true,
//                'roles' => [UserRoleEnum::ADMIN],
//            ]
//        ];
//    }
}
