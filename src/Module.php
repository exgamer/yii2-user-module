<?php
namespace concepture\yii2user;

use Yii;

/**
 * Class Module
 * @package concepture\yii2user
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'concepture\yii2user\web\controllers';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['yii2user'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'ru-RU',
            'basePath' => '@vendor/concepture/yii2user/messages',
        ];

    }
}