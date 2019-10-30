<?php
namespace concepture\yii2user\web\controllers;

/**
 * Class UserCredentialController
 * @package concepture\yii2user\web\controllers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
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