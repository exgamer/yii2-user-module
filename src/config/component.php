<?php

return [
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'cache' => 'cache',
        'itemTable'=>'user_auth_item',
        'ruleTable'=>'user_auth_rule',
        'assignmentTable'=>'user_auth_assignment', // роли
        'itemChildTable'=>'user_auth_item_child',
    ],
    'userService' => [
        'class' => 'concepture\yii2user\services\UserService'
    ],
    'userCredentialService' => [
        'class' => 'concepture\yii2user\services\UserCredentialService'
    ],
    'userSocialAuthService' => [
        'class' => 'concepture\yii2user\services\UserSocialAuthService'
    ],
    'authService' => [
        'class' => 'concepture\yii2user\services\AuthService'
    ],
    'userRoleService' => [
        'class' => 'concepture\yii2user\services\UserRoleService'
    ],
    'emailHandbookService' => [
        'class' => 'concepture\yii2user\services\EmailHandbookService'
    ],
    'rbacService' => [
        'class' => 'concepture\yii2user\services\RbacService'
    ],
];
