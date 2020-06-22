<?php
use concepture\yii2logic\enum\AccessEnum;
use concepture\yii2logic\enum\PermissionEnum;
use concepture\yii2user\rbac\rules\DomainRule;
use concepture\yii2user\rbac\rules\StaffRule;

return [
    'excluded_controller_names' => [
        'DEFAULT',
        'CHANGELOCK',
        'WIDGETS',
        'CRUD',
        'MAGICMODAL',
    ],
    'permissions' => [
        AccessEnum::SUPERADMIN,
        AccessEnum::ADMIN,
        AccessEnum::EDITOR,
        AccessEnum::READER,
        AccessEnum::STAFF,
    ],
    'default_roles' => [
        'ADMIN',
        'EDITOR',
        'READER',
        'STAFF' => [
            'rule' => StaffRule::class,
        ],
    ],
    'default_dependencies' => [
        'ADMIN' => [
            'EDITOR',
            'READER',
            'STAFF',
            'DOMAIN',
        ],
        'EDITOR' => [
            'READER',
            'STAFF',
        ],
        'STAFF' => [
            'READER',
        ],
    ],
    'dependencies' => [
        AccessEnum::SUPERADMIN => '*',
        AccessEnum::ADMIN => '*',
        AccessEnum::EDITOR => PermissionEnum::EDITOR,
        AccessEnum::READER => PermissionEnum::READER,
        AccessEnum::STAFF => PermissionEnum::STAFF,
    ],
];
