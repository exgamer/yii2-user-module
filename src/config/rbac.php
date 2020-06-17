<?php
use concepture\yii2logic\enum\AccessEnum;
use concepture\yii2logic\enum\PermissionEnum;
use concepture\yii2user\rbac\rules\DomainEditorRule;
use concepture\yii2user\rbac\rules\DomainReaderRule;
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
        AccessEnum::DOMAINREADER,
        AccessEnum::DOMAINEDITOR,
    ],
    'default_roles' => [
        'ADMIN',
        'EDITOR',
        'READER',
        'STAFF' => [
            'rule' => StaffRule::class,
        ],
        'DOMAINEDITOR' => [
            'rule' => DomainEditorRule::class,
        ],
        'DOMAINREADER' => [
            'rule' => DomainReaderRule::class,
        ],
    ],
    'default_dependencies' => [
        'ADMIN' => [
            'EDITOR',
            'READER',
            'STAFF',
            'DOMAINEDITOR',
            'DOMAINREADER',
        ],
        'EDITOR' => [
            'READER',
            'STAFF',
        ],
        'STAFF' => [
            'READER',
        ],
        'DOMAINEDITOR' => [
            'DOMAINREADER',
        ],
    ],
    'dependencies' => [
        AccessEnum::SUPERADMIN => '*',
        AccessEnum::ADMIN => '*',
        AccessEnum::EDITOR => PermissionEnum::EDITOR,
        AccessEnum::READER => PermissionEnum::READER,
        AccessEnum::STAFF => PermissionEnum::STAFF,
        AccessEnum::DOMAINEDITOR => PermissionEnum::DOMAINEDITOR,
        AccessEnum::DOMAINREADER => PermissionEnum::DOMAINREADER,
    ],
];