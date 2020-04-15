<?php
use concepture\yii2user\enum\AccessEnum;

return [
    'permissions' => [
        AccessEnum::SUPERADMIN,
        AccessEnum::ADMIN,
    ],
//    AccessEnum::FEEDBACK_ADMIN ,
//    AccessEnum::FEEDBACK_EDITOR ,
//    AccessEnum::FEEDBACK_READER ,
//    AccessEnum::FEEDBACK_STAFF ,
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