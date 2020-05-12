Настроить пользователя можно если создать фаил common/config/user.php
и настроить следующим образом
```php
<?php

$enableAutoLogin = false;
$enableSession = false;
if (Yii::$app instanceof \yii\web\Application){
    $enableAutoLogin = true;
    $enableSession = true;
}
return [
    'class' => 'concepture\yii2user\WebUser',
    'identityClass' => 'concepture\yii2user\models\User',
    'enableAutoLogin' => $enableAutoLogin,
    'enableSession' => $enableSession,
    'loginUrl' => ['/site/login'],
//    'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
];
```