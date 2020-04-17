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
     * дефолтные экшоны чтения данных
     * @var string[]
     */
    static $_read_actions = [
        'index',
        'list',
        'view',
    ];

    /**
     * дефолтные экшоны модификации данных
     * @var string[]
     */
    static $_edit_actions = [
        'create',
        'update',
        'delete',
        'undelete',
        'status-change',
        'image-upload',
        'image-delete',
        'editable-column',
        'create-validate-attribute',
        'update-validate-attribute',
    ];

    /**
     * экшоны модуля сортировки
     * @var string[]
     */
    static $_sort_actions = [
        'sort',
        'position-sort-index',
    ];

    public function getPermissionsByAction($controller, $action)
    {
        if (in_array($action, static::$_read_actions) ){
            return [
                AccessEnum::SUPERADMIN,
                AccessEnum::ADMIN,
                static::getAccessPermission($controller, PermissionEnum::ADMIN),
                static::getAccessPermission($controller, PermissionEnum::STAFF),
                static::getAccessPermission($controller, PermissionEnum::EDITOR),
                static::getAccessPermission($controller, PermissionEnum::READER),
            ];
        }

        if (in_array($action, static::$_edit_actions) ){
            return [
                AccessEnum::SUPERADMIN,
                AccessEnum::ADMIN,
                static::getAccessPermission($controller, PermissionEnum::ADMIN),
                static::getAccessPermission($controller, PermissionEnum::STAFF),
                static::getAccessPermission($controller, PermissionEnum::EDITOR),
            ];
        }

        if (in_array($action, static::$_sort_actions) ){
            return [
                AccessEnum::SUPERADMIN,
                AccessEnum::ADMIN,
                static::getAccessPermission($controller, PermissionEnum::ADMIN),
                static::getAccessPermission($controller, PermissionEnum::EDITOR),
            ];
        }
    }


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
            'actions' => static::$_read_actions,
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
            'actions' => static::$_edit_actions,
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
            'actions' => static::$_sort_actions,
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