<?php
namespace concepture\yii2user\web\controllers;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserCredentialController extends Controller
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);

        return $actions;
    }
}