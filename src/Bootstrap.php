<?php
namespace concepture\yii2user;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    //Метод, который вызывается автоматически при каждом запросе
    public function bootstrap($app)
    {
        //user component
        $userConfig = require_once __DIR__ . '/config/user.php';
        Yii::$container->set('yii\web\User', $userConfig);
        //загружаем компоненты
        $components  = require_once __DIR__ . '/config/component.php';
        Yii::$app->setComponents($components);



//        //Правила маршрутизации
//        $urls = require_once __DIR__ . '/config/route.php';
//        $app->getUrlManager()->addRules($urls, false);
//        /*
//         * Регистрация модуля в приложении
//         * (вместо указания в файле frontend/config/main.php
//         */
//        $app->setModule('cuser', 'concepture\user\Module');
    }
}