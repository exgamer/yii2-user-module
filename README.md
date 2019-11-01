# yii2-user-module

# concepture_engine

https://klisl.com/yii2-extension.html


composer.json

    "require": {
        "concepture/core" : "*",
        "concepture/yii2-core" : "*",
        "concepture/yii2-user" : "*"
    }
    "extra": {
        "bootstrap": "concepture\\user\\Bootstrap"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://asset-packagist.org"
        },
        {
            "type": "path",
            "url": "concepture/yii2-user",
            "options": {
                "symlink": true
            }
        }
    ]
    
Подключение

"require": {
    "concepture/yii2-user-module" : "dev-master"
},
    

Миграции
 php yii migrate/up --migrationPath=@concepture/yii2user/console/migrations --interactive=0
 
Подключение модуля для админки

     'modules' => [
         'user' => [
             'class' => 'concepture\yii2user\Module'
         ],
     ],
     
Для переопределния контроллера добавялем в настройки модуля

     'modules' => [
         'static' => [
            'class' => 'concepture\yii2user\Module',
            'controllerMap' => [
                'user' => 'backend\controllers\UserController'
            ],
         ],
     ],

            
Для переопределния папки с представленяими добавялем в настройки модуля

     'modules' => [
         'static' => [
             'class' => 'concepture\yii2user\Module',
             'viewPath' => '@backend/views'
         ],
     ],
 
