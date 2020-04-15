<?php
namespace concepture\yii2user\helpers;


use concepture\yii2handbook\actions\PositionSortIndexAction;
use concepture\yii2logic\helpers\ClassHelper;
use concepture\yii2user\enum\AccessEnum;
use concepture\yii2user\enum\PermissionEnum;
use kamaelkz\yii2admin\v1\actions\EditableColumnAction;
use kamaelkz\yii2admin\v1\actions\SortAction;
use Yii;


/**
 * Класс содержит вспомогательные методы для рабоыт с rbac
 * @package concepture\yii2user\helpers
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class AccessHelper
{
    /**
     * Возвращает базовые правила доступа
     * @param $controller
     * @return array
     */
    public static function getDefaultAccessRules($controller)
    {
        $rules = [];
        /**
         * Просмотр
         */
        $rules[] = [
            'actions' => [
                'index',
                'list',
                'view',
            ],
            'allow' => true,
            'roles' => [
                AccessEnum::SUPERADMIN,
                AccessEnum::ADMIN,
                static::getAccessPermission($controller, PermissionEnum::ADMIN),
                static::getAccessPermission($controller, PermissionEnum::STAFF),
                static::getAccessPermission($controller, PermissionEnum::EDITOR),
                static::getAccessPermission($controller, PermissionEnum::READER),
            ],
        ];
        /**
         * Модификация
         */
        $rules[] = [
            'actions' => [
                'create',
                'update',
                'delete',
                'undelete',
                'status-change',
                'image-upload',
                'image-delete',
                EditableColumnAction::actionName(),
            ],
            'allow' => true,
            'roles' => [
                AccessEnum::SUPERADMIN,
                AccessEnum::ADMIN,
                static::getAccessPermission($controller, PermissionEnum::ADMIN),
                static::getAccessPermission($controller, PermissionEnum::STAFF),
                static::getAccessPermission($controller, PermissionEnum::EDITOR),
            ],
        ];
        /**
         * Сортировка
         */
        $rules[] = [
            'actions' => [
                SortAction::actionName(),
                PositionSortIndexAction::actionName()
            ],
            'allow' => true,
            'roles' => [
                AccessEnum::SUPERADMIN,
                AccessEnum::ADMIN,
                static::getAccessPermission($controller, PermissionEnum::ADMIN),
                static::getAccessPermission($controller, PermissionEnum::EDITOR),
            ],
        ];

        return $rules;
    }

    /**
     * Возвращает значение полномочия для переданного контроллера
     * @param $controller
     * @param $permission
     * @return string
     */
    public static function getAccessPermission($controller, $permission)
    {
        $name = ClassHelper::getShortClassName($controller, 'Controller', true);


        return $name . "_" . $permission;
    }
}