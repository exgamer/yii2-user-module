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
                      'validateAuthState' => false, // вот это нужно добавлять в конфиг!!!
                      'clientId' => '9999a44d4e5fc12b742f',
                      'clientSecret' => '1196f1cc2b3801dd384100de169220e45a7ed183',
                  ],
                  'vkontakte' => [
                      'class' => 'yii\authclient\clients\VKontakte',
                      'validateAuthState' => false,
                      'clientId' => '7342228 ',
                      'clientSecret' => 'X237p3EgVMjBVQM03WJn',
                  ],
                  'google' => [
                      'class' => 'yii\authclient\clients\Google',
                      'validateAuthState' => false,
                      'clientId' => '247411900945-nk50mq6gae29i26vl8r752tav14cv6s2.apps.googleusercontent.com',
                      'clientSecret' => 'SZQcbGdRKNPBsMLSwjp9RJLK',
                  ],
                  'yandex' => [
                      'class' => 'yii\authclient\clients\Yandex',
                      'validateAuthState' => false,
                      'clientId' => '662b41f23de54e798dac3240f187e8a7',
                      'clientSecret' => 'c1d37b5e597f46a1af11fe9459148925',
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
                    'class' => 'concepture\yii2user\actions\SocialAuthAction',
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

Если после атворизации нужно показать определенный блок страницы можно поставить якорь

<a name="social-anchor"></a>

```php

<?php

    echo yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['site/auth'],
    ]);

```
