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
                  'instagram' => [
                      'class' => 'concepture\yii2user\authclients\Instagram',
                      'validateAuthState' => false,
                      'clientId' => '570749406859198',
                      'clientSecret' => 'c98ae1e325cd5f4d959e92c8f1def251',
                  ],
                    'mailru' => [
                        'class' => 'concepture\yii2user\authclients\MailRu',
                        'validateAuthState' => false,
                        'clientId' => '570749406859198',
                        'clientSecret' => 'c98ae1e325cd5f4d959e92c8f1def251',
                    ],
                    'odnoklassniki' => [
                        'class' => 'concepture\yii2user\authclients\Odnoklassniki',
                        'applicationKey' => getenv('ODNOCLASSNIKI_APP_KEY'),
                        'clientId' => getenv('ODNOCLASSNIKI_CLIENT_ID'),
                        'clientSecret' => getenv('ODNOCLASSNIKI_SECRET'),
                    ],
                ],
            ]
        ]               
    ];

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

4. Если используется инстаграмм в стилях должен быть класс instagram с иконкой !!!!!!!!!!
5. Если используется mailru в стилях должен быть класс mailru с иконкой !!!!!!!!!!
6. Если используется odnoklassniki в стилях должен быть класс odnoklassniki с иконкой !!!!!!!!!!