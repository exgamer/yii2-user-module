Настроить authHelper сервисa авторизации можно через definitions
в config/main.php
```php
<?php

return [
    'container' => [
        'definitions' =>    
        [ # сервис авторизации
             'concepture\yii2user\services\AuthService' => [
                 'class' => 'concepture\yii2user\services\AuthService',
                 'authHelper' => \concepture\yii2user\services\helpers\DomainAuthHelper::class
             ]
        ]
    ],

];
```

Либо обьявить сервис в компонентах приложения с нужными настройками