<?php

namespace concepture\yii2user\web\controllers;

use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2logic\actions\web\AutocompleteListAction;
use concepture\yii2logic\actions\web\StatusChangeAction;
use concepture\yii2logic\actions\web\UndeleteAction;
use kamaelkz\yii2cdnuploader\actions\web\ImageDeleteAction;
use kamaelkz\yii2cdnuploader\actions\web\ImageUploadAction;

/**
 * Class UserController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserController extends Controller
{
    protected function getAccessRules()
    {
        return [
            [
                'actions' => ['index', 'create','update', 'view','delete', 'list', 'undelete', 'status-change', 'image-upload', 'image-delete'],
                'allow' => true,
                'roles' => [UserRoleEnum::ADMIN],
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['list'] = AutocompleteListAction::class;
        $actions['status-change'] = StatusChangeAction::class;
        $actions['undelete'] = UndeleteAction::class;
        $actions['image-upload'] = ImageUploadAction::class;
        $actions['mage-delete'] = ImageDeleteAction::class;

        return $actions;
    }
}
