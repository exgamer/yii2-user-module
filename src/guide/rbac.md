# yii2-user-module

# RBAC

### !!! в build.sh перед миграциями пакета 

php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0 --alias=$ALIAS
if [[ $? -ne 0 ]] ; then
    exit 1
fi
php yii user/rbac/generate  --interactive=0 --alias=$ALIAS
if [[ $? -ne 0 ]] ; then
    exit 1
fi

###



# Миграции для инициализации таблиц rbac

 php yii migrate --migrationPath=@yii/rbac/migrations
 
# Таблицы

## user_auth_item - роли и полномочия системы
## user_auth_rule - правила системы
## user_auth_assignment - роли назначенные пользователям
## user_auth_item_child - отношение ролей и полномочий (родительский - дочерний)
 
# Подключение модуля для консоли

     'modules' => [
        'user' => [
            'class' => 'concepture\yii2user\Module',
            'controllerMap' => [
                'rbac' => 'concepture\yii2user\console\controllers\RbacController',
            ]
        ],
     ],

# Создание enum для полномочий. Создать фаил  common/enum/AccessEnum
## В классе перечислены константы в формате <Название контроллера сущности>_<полномочие> заглавными буквами
## т.е. BOOKMAKER_ADMIN будет распознано как 
## роль - BOOKMAKER, 
## полномочие - BOOKMAKER_ADMIN, 
## !!! Если нужно добавить роль без полномочий делаем так     const SUPERADMIN = "SUPERADMIN"; 


```php
<?php

namespace common\enum;

use concepture\yii2user\enum\AccessEnum as Base;
use Yii;

/**
 * Class AccessEnum
 * @package common\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class AccessEnum extends Base
{

    const BOOKMAKER_ADMIN = "BOOKMAKER_ADMIN";
    const BOOKMAKER_READER = "BOOKMAKER_READER";
    const BOOKMAKER_EDITOR = "BOOKMAKER_EDITOR";
    const BOOKMAKER_STAFF = "BOOKMAKER_STAFF";

}

```

# При необходимости создать правило для полномочия
# Пример 
```php
namespace common\rbac\rules;

use yii\rbac\Rule;

/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 * Переменную $params мы передаем сюда, когда вызываем \Yii::$app->user->can('role', $param = ['postOwnerId' => 10]);
 */
class UpdatePostRule extends Rule
{
    public $name = 'rule_update-post';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['postOwnerId']) ? $params['postOwnerId'] == $user : false;
    }
}

```

# Создание конфига для кастомных настроек rbac. Создать фаил  common/config/rbac.php
## Название полномочия должно соответствовать следующему паттерну <название контроллера без ключевого слова Controller>_<название полномочия без разделителей>. например SOME_PERMISSION

```php
<?php

use common\enum\AccessEnum;

return [
    'excluded_controller_names' => [
        'DEFAULT',
        'CHANGELOCK',
    ],
    'permissions' => [
        AccessEnum::BOOKMAKER_ADMIN => [
    //        'description' => 'трулала',
            'rule' => \concepture\yii2user\rbac\rules\TestRule::class
        ],
        AccessEnum::BOOKMAKER_EDITOR ,
        AccessEnum::BOOKMAKER_READER ,
        AccessEnum::BOOKMAKER_STAFF ,
    ],
    'dependencies' => [
        AccessEnum::BOOKMAKER_ADMIN => [
            AccessEnum::BOOKMAKER_READER,
            AccessEnum::BOOKMAKER_STAFF ,
        ],
    ],
];

```

# Контроллеры админки должны наследоваться от kamaelkz\yii2admin\v1\controllers\BaseController
## Для стандартных actions index, view, create, update, delete, undelete, status-change по умолчанию будут выставлены правила доступа посмотреть можно в 
## concepture\yii2user\helpers\AccessHelper::getDefaultAccessRules()

## Для дополнительных actions в контроллере нужно определить правила доступа через метод getAccessRules()

```php
<?php
use concepture\yii2user\enum\AccessEnum;
use concepture\yii2user\helpers\AccessHelper;
use kamaelkz\yii2admin\v1\controllers\BaseController;

class SomeController extends BaseController
{

    protected function getAccessRules(): array
    {
        return ArrayHelper::merge(
            parent::getAccessRules(),
            [
                [
                    'actions' => [
                        'custom-action',
                    ],
                    'allow' => true,
                    'roles' => [AccessHelper::getAccessPermission($this, 'PERMISSION'), AccessEnum::ADMIN],
                ],
            ]
        );
    }
}
```

# команда для генерации ролей привилеий и правил
   php yii user/rbac/generate 

# Првоерка на доступ к роуту или экшену

```php
<?php
     AccessHelper::checkAcces($route));
```