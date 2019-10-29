<?php

namespace concepture\yii2user\web\controllers;

/**
 * UserRoleController implements the CRUD actions for UserRole model.
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
