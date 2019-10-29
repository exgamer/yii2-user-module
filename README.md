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

y
 
 Настройка автообновления пакета на https://packagist.org/ws 
 
 В репозитории гитхаб переходим в настройки репозитория
 переходим в пункт webhooks  и добавляем новый
 вводим url и тип данных из (заходим на страницу пакета packagist если автообновление не настроено быдет ссылка оттуда копируем)
 вводим апи ключ из профиля packagist