# Гаид по авторизации через соц сеть через свой сервис

1. У контроллеров фронта подключить поведение 
  concepture\yii2user\filters\SocialAuthFilter для авторизации

```php
<?php

    abstract class BaseController extends \yii\web\Controller
    {    
        public function behaviors()
        {
            $b = parent::behaviors();
            $b['socialAuthFilter'] = [
                'class' => SocialAuthFilter::class,
            ];
    
            return $b;
        }
}

```

2. Для твига подключить concepture\yii2user\components\twig\JwtHelperExtension

3. В common/config/params.php

```php
<?php

    return [
        'JWT_SECRET' => getenv('JWT_SECRET'),
        'JWT_EXPIRE' => getenv('JWT_EXPIRE'),
        'SSO_APP_ID' => getenv('SSO_APP_ID'),
    ];

```

4. В .env добавить данные SSO_APP_ID ид приложения на сервисе атворизации
 JWT_SECRET - должен совпадать с сервисом авторизации
 JWT_EXPIRE - должен совпадать с сервисом авторизации
 SSO_APP_ID - id приложения на сервисе авторизации
 
```
JWT_SECRET=test
JWT_EXPIRE=86400
SSO_APP_ID=1

```

5. на представлении подключить скрипт 
    Клиенты: github, mailru, vkontakte, google, yandex, odnoklassniki
```twig
    <script defer src="http://social-auth.loc/auth.min.js"></script>

    <div id="socialAuth" data-link-label="1" data-x-token="{{ jwt_token() }}" data-auth-host="http://social-auth.loc" data-redirect-url="{{ app.request.absoluteUrl }}" data-clients="github,mailru,vkontakte"></div>
```

6. По адресу http://social-auth.loc/constructor можно сгенерить код для вставки