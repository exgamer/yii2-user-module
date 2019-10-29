<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2logic\actions\web\AutocompleteListAction;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    protected function getAccessRules()
    {
        return [
            [
                'actions' => ['index', 'create','update', 'view','delete', 'list'],
                'allow' => true,
                'roles' => [UserRoleEnum::ADMIN],
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['list'] = AutocompleteListAction::class;

        return $actions;
    }
}
