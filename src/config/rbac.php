<?php
use concepture\yii2user\enum\AccessEnum;
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
    ],
    'default_roles' => [
        'ADMIN',
        'EDITOR',
        'READER',
        'STAFF' => [
            'rule' => StaffRule::class,
        ]
    ],
    'default_dependencies' => [
        'ADMIN' => [
            'EDITOR',
            'READER',
            'STAFF',
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
    ],
];