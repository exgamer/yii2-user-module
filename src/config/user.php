<?php

$enableAutoLogin = false;
$enableSession = false;
if (Yii::$app instanceof \yii\web\Application){
    $enableAutoLogin = true;
    $enableSession = true;
}
$params = [
    'class' => 'concepture\yii2user\WebUser',
    'identityClass' => 'concepture\yii2user\models\User',
    'enableAutoLogin' => $enableAutoLogin,
    'enableSession' => $enableSession,
    'loginUrl' => 'site/login',
];

if (Yii::$app instanceof \yii\web\Application){
    $params['identityCookie'] = ['name' => '_identity-app', 'httpOnly' => true];
}

return $params;