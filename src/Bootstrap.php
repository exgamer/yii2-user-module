<?php
namespace concepture\yii2user;

use Yii;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * Файл первичной настройки модуля
 *
 * Class Bootstrap
 * @package concepture\yii2user
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class Bootstrap implements BootstrapInterface
{
    //Метод, который вызывается автоматически при каждом запросе
    public function bootstrap($app)
    {
        //user component
        $commonUserConfig = [];
        $baseConfig = require_once __DIR__ . '/config/user.php';
        $commonUserConfigPath = Yii::getAlias('@common') . "/config/user.php";
        if (file_exists($commonUserConfigPath)) {
            $commonUserConfig = require_once $commonUserConfigPath;
        }

        $frontUserConfig = [];
        try {
            $path = \Yii::$app->basePath;
            $path = explode('/', $path);
            $alias = array_pop($path);
            $frontUserConfigPath = Yii::getAlias("@{$alias}") . "/config/user.php";
            if (file_exists($frontUserConfigPath)) {
                $frontUserConfig = require_once $frontUserConfigPath;
            }
        }catch (\Exception $ex) {

        }

        $userConfig = ArrayHelper::merge($baseConfig,$commonUserConfig, $frontUserConfig);
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