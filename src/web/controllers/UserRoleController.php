<?php

namespace concepture\yii2user\web\controllers;

/**
 * Class UserRoleController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleController extends Controller
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);

        return $actions;
    }
}
