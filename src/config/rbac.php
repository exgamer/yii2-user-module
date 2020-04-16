<?php
use concepture\yii2user\enum\AccessEnum;

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
        'STAFF',
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