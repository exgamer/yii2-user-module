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
 
 Для переопределния любого класса можно вооспользоваться инекцией зависимостей через config.php
 К примеру подменить модель StaticBlock на свой
 
     <?php
     return [
         'container' => [
             'definitions' => [
                 'concepture\yii2static\models\StaticBlock' => ['class' => 'backend\models\StaticBlock'],
             ],
         ],
     ]





# Авторизация через соц сети
1. В конфиге добавить 

```php
<?php
        
    $config =  [
        'components' => [
            'authClientCollection' => [
                'class' => 'yii\authclient\Collection',
                'clients' => [
                  'github' => [
                      'class' => 'yii\authclient\clients\GitHub',
                      'validateAuthState' => false,
                      'clientId' => '9999a44d4e5fc12b742f',
                      'clientSecret' => '1196f1cc2b3801dd384100de169220e45a7ed183',
                  ],
    
                ],
            ]
        ]               
    ]

````

2. В контроллере добавить экшн

```php

<?php

    namespace kamaelkz\yii2admin\v1\controllers;
    
    use Yii;
    
    class DefaultController extends BaseController
    {
    
    
        /**
         * {@inheritdoc}
         */
        public function actions()
        {
            return [
                'auth' => [
                    'class' => 'yii\authclient\AuthAction',
                    'successCallback' => [$this, 'onAuthSuccess'],
                ],
            ];
        }
    
        public function onAuthSuccess($client)
        {
            Yii::$app->authService->onSocialAuthSuccess($client);
        }
    }

```

3. вывести на представлении виджет

```php

<?php

    echo yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['site/auth'],
        'popupMode' => false,
    ]);

```
