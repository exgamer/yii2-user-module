<?php
namespace concepture\yii2user\services\traits;

use concepture\yii2logic\console\traits\OutputTrait;
use concepture\yii2logic\helpers\ClassHelper;
use concepture\yii2logic\enum\PermissionEnum;
use concepture\yii2user\forms\UserAuthPermissionForm;
use concepture\yii2user\forms\UserAuthRoleForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Треит с методами для генерации ролей и полномочий для rbac
 *
 * Trait RbacGenerateTrait
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
trait RbacGenerateTrait
{
    use OutputTrait;

    /**
     * Возвращает массив классов контроллеров
     * из backend, пакетов concepture и kamaelkz
     *
     * @return array
     */
    protected function getControllerClasses()
    {
        $projectControllers = FileHelper::findFiles(Yii::getAlias('@backend'), ['recursive' => true, 'only'=>['*Controller.php']]);
        $c = [];
        $conceptureControllers = FileHelper::findFiles(Yii::getAlias('@vendor/concepture'), ['recursive' => true, 'only'=>['*Controller.php']]);
        $controllers = ArrayHelper::merge($projectControllers, $conceptureControllers);
        $kamaelControllers = FileHelper::findFiles(Yii::getAlias('@vendor/kamaelkz'), ['recursive' => true, 'only'=>['*Controller.php']]);
        $controllers = ArrayHelper::merge($controllers, $kamaelControllers);
        foreach ($controllers as $controller){
            $handle = fopen($controller, "r");
            if ($handle) {
                $ns = $class = null;
                while (($line = fgets($handle)) !== false) {
                    if (strpos($line, 'namespace') === 0) {
                        $parts = explode(' ', $line);
                        $ns = rtrim(trim($parts[1]), ';');

                    }

                    if (strpos($line, 'class') === 0) {
                        $parts = explode(' ', $line);
                        $class = rtrim(trim($parts[1]), ';');

                    }

                    if ($ns && $class){
                        $c[] = $ns . "\\" . $class;
                        break;
                    }
                }
                fclose($handle);
            }
        }

        return $c;
    }

    /**
     * Возвращает web контроллеры
     * @return array
     */
    protected function getWebControllerClasses()
    {
        $result = [];
        $controllers = $this->getControllerClasses();
        foreach ($controllers as $controller){
            /**
             * @TODO кастыль тут надо как то проверять на то чтобы класс был наследником yii\web\Controller
             */
            if( strpos($controller, 'console') === false){
                $result[] = $controller;
                continue;
            }
        }

        return $result;
    }

    /**
     * Возвращает названия контроллеров
     * @return array
     */
    protected function getControllerNames()
    {
        $result = [];
        $controllers = $this->getWebControllerClasses();
        foreach ($controllers as $controller){
            $tmp = ClassHelper::getShortClassName($controller);
            $result[] = strtoupper(str_replace("Controller", "", $tmp));
        }

        $result = array_unique($result);

        return $result;
    }

    /**
     * Возвращает дефолтный конфиг для rbac
     * @return array|null
     * @throws \Exception
     */
    protected function getDefaultConfig()
    {
        static $defaultConfig = null;
        if (! empty($defaultConfig)){
            return $defaultConfig;
        }

        $baseConfig = require_once __DIR__ . '/../../config/rbac.php';
        $commonConfig = [];
        $commonConfigPath = Yii::getAlias('@common') . "/config/rbac.php";
        if (file_exists($commonConfigPath)) {
            $commonConfig = require_once $commonConfigPath;
        }

        $defaultConfig = ArrayHelper::merge($baseConfig, $commonConfig);
        if (! isset($defaultConfig['default_roles'])){
            throw new \Exception("default roles not set");
        }

        if (! isset($defaultConfig['default_dependencies'])){
            throw new \Exception("default dependencies not set");
        }

        $defaultRoles = $defaultConfig['default_roles'];
        $defaultDependencies = $defaultConfig['default_dependencies'];
        $access = [];
        $dependencies = [];
        $persmissions = [];
        $permissionsDependencies = [];
        $domains = Yii::$app->domainService->catalog('id', 'alias');
        foreach ($domains as $alias) {
            foreach ($defaultRoles as $key => $value) {
                $role = $key;
                $config = null;
                if (filter_var($key, FILTER_VALIDATE_INT) !== false) {
                    $role = $value;
                }else{
                    $config = $value;
                }

                $roleName = strtoupper($alias) . "_" . $role;
                $perRoleName = str_replace('_', '', $roleName);
                if ($config){
                    $access[$roleName] = $config;
                    $persmissions[$perRoleName] = $config;
                }else{
                    $access[] = $roleName;
                    $persmissions[] = $perRoleName;
                }

                if (isset($defaultDependencies[$role])){
                    foreach ($defaultDependencies[$role] as $dependentRole){
                        $dependencies[$roleName][] = strtoupper($alias) . "_" . $dependentRole;
                        $permissionsDependencies[$perRoleName][] = strtoupper($alias) . $dependentRole;
                    }
                    $tmp = array_unique($dependencies[$roleName]);
                    $dependencies[$roleName] = $tmp;
                }
            }
        }

        $defaultConfig['default_roles'] = ArrayHelper::merge($defaultRoles, $access);
        $defaultConfig['default_dependencies'] = ArrayHelper::merge($defaultDependencies, $dependencies);

        $defaultConfig['permissions'] = ArrayHelper::merge($defaultConfig['permissions'], $persmissions);
        $defaultConfig['dependencies'] = ArrayHelper::merge($defaultConfig['dependencies'], $permissionsDependencies);

        return $defaultConfig;
    }

    /**
     * Сгенерить конфиг для считанных контроллеров
     * @return array
     * @throws \Exception
     */
    protected function generateControllerAccessConfig()
    {
        $controllerNames = $this->getControllerNames();
        $defaultConfig = $this->getDefaultConfig();
        $defaultRoles = $defaultConfig['default_roles'];
        $defaultDependencies = $defaultConfig['default_dependencies'];
        $customPermissions = $defaultConfig['custom_permissions'] ?? [];
        $excludedControllerNames = $defaultConfig['excluded_controller_names'];
        $controllerConfig = [];
        $access = [];
        $dependencies = [];
        foreach ($controllerNames as $name){
            if (in_array($name, ["", null])){
                continue;
            }

            if (array_search($name, $excludedControllerNames) !== false){
                continue;
            }

            foreach ($defaultRoles as $key => $value){
                $role = $key;
                $config = null;
                if (filter_var($key, FILTER_VALIDATE_INT) !== false) {
                    $role = $value;
                }else{
                    $config = $value;
                }

                $roleName = $name . "_" . $role;
                if ($config){
                    $access[$roleName] = $config;
                }else{
                    $access[] = $roleName;
                }

                if (isset($defaultDependencies[$role])){
                    foreach ($defaultDependencies[$role] as $dependentRole){
                        $dependencies[$roleName][] = $name . "_" . $dependentRole;
                    }

                }
            }

            //генерация кастомных полномочий
            foreach ($customPermissions as $controller => $permission) {
                if (filter_var($controller, FILTER_VALIDATE_INT) !== false && ! is_array($permission)) {
                    $controllerConfig['generated_custom_permissions'][] = $permission;
                    continue;
                }

                if ($controller !== $name) {
                    continue;
                }

                if (! is_array($permission)) {
                    $permission = [$permission];
                }

                foreach ($permission as $p) {
                    $p = strtoupper($p);
                    $controllerConfig['generated_custom_permissions'][] = $name . '_' . $p;
                    $domains = Yii::$app->domainService->catalog('id', 'alias');
                    foreach ($domains as $alias) {
                        $controllerConfig['generated_custom_permissions'][] = $name . "_" .strtoupper($alias) . "_" . $p;
                    }
                }
            }
        }

        $controllerConfig['permissions'] = $access;
        $controllerConfig['dependencies'] = $dependencies;
        $controllerConfig['generated_custom_permissions'] = array_unique($controllerConfig['generated_custom_permissions']);

        return $controllerConfig;
    }

    /**
     * Возвращает конфиг на основе которого будут сгенерены полномочия и роли rbac
     * @return array
     */
    protected function getAccessConfig()
    {
        $controllerConfig = $this->generateControllerAccessConfig();
        $defaultConfig = $this->getDefaultConfig();
        $config = ArrayHelper::merge($defaultConfig, $controllerConfig);

        return $config;
    }

    /**
     * Проверка на необходимость генерации ролей
     * @return bool
     */
    protected function isNeedGenerate()
    {
        $config = $this->getAccessConfig();
        $accessData = $config['permissions'] ??[];
        $customPermissions = $config['generated_custom_permissions'] ??[];
        $customRoles = $config['custom_roles'] ??[];
        $accessData = ArrayHelper::merge($accessData, $customPermissions, $customRoles);
        $result = [];
        foreach ($accessData as $key => $value) {
            if (! is_array($value)) {
                $result[] = $value;
            }else{
                $result[] = $key;
            }
        }

        $db = $this->getAuthManager()->db;
        $transaction = $db->beginTransaction();
        $roles = $db->createCommand("SELECT `name` FROM user_auth_item;")->queryAll();
        $usedItems = ArrayHelper::map($roles, 'name', 'name');
        $diff = array_diff($result, $usedItems);
        if (! empty($diff)){
            $this->outputSuccess( "Next new permissions found: " . implode(',' , $diff));
            return true;
        }

        return false;
    }

    /**
     * генерация ролей и полномочий из конфигов
     */
    public function generate()
    {
        if (! $this->isNeedGenerate()) {
            $this->outputSuccess( "No changes in Rbac. Skip generating.");
            return;
        }

        $db = $this->getAuthManager()->db;
        $config = $this->getAccessConfig();
        $accessData = $config['permissions'] ??[];
        $dependenciesData = $config['dependencies'] ??[];
        $customPermissions = $config['generated_custom_permissions'] ??[];
        $customRoles = $config['custom_roles'] ??[];
        $newItems = [];
        $allData = ArrayHelper::merge($accessData, $customPermissions, $customRoles);
        foreach ($allData as $key => $value) {
            $access = $key;
            if (filter_var($key, FILTER_VALIDATE_INT) !== false) {
                $access = $value;
            }

            $newItems[$access] = $access;
        }

        //првоерка привязана ли роль которой нет в конфиге к юзерам
        $roles = $db->createCommand("SELECT item_name FROM user_auth_assignment GROUP BY item_name;")->queryAll();
        $usedItems = ArrayHelper::map($roles, 'item_name', 'item_name');
        $diff = array_diff($usedItems, $newItems);
        if (! empty($diff)){
            throw new \Exception(implode(',' , $diff) . " these roles is used in user_auth_assignment, but not found in new rules");
        }

//        $transaction = $db->beginTransaction();
        try {
            $db->createCommand("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE user_auth_rule;")->execute();
            $db->createCommand("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE user_auth_item_child;")->execute();
            $db->createCommand("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE user_auth_item;")->execute();

            $this->outputSuccess( "Rbac roles generate start");
            $count = count($accessData);
            Console::startProgress(0, $count);
            $i = 0;
            foreach ($accessData as $key => $value){
                $access = $key;
                $accessConfig = [];
                /**
                 * Если ключ целое число значит у полномочия нет настроек
                 */
                if (filter_var($key, FILTER_VALIDATE_INT) !== false) {
                    $access = $value;
                }else{
                    $accessConfig = $value;
                }

                /**
                 * Добавляем роль
                 */
                $roleForm = $this->getRoleForm($access, $accessConfig);
                $role = $this->addRole($roleForm);
                /**
                 * Добавялем полномочие (если есть)
                 */
                $permissionForm = $this->getPermissionForm($access, $accessConfig);
                if ($permissionForm){
                    /**
                     * Добавялем правило (если есть)
                     */
                    $ruleClass = $this->getRuleClassFromConfig($access, $accessConfig);
                    if ($ruleClass){
                        $rule = $this->addRule($ruleClass);
                        $permissionForm->ruleName = $rule->name;
                    }

                    $permission = $this->addPermission($permissionForm);
                    $this->addChild($role, $permission);
                }

                Console::updateProgress($i + 1 , $count);
                $i++;
            }

            /**
             * Добавляем зависимости полномочий
             */
            $this->outputSuccess( "Rbac permissions dependencies generate start");
            $count = count($dependenciesData);
            Console::startProgress(0, $count);
            $i = 0;
            foreach ($dependenciesData as $parent => $childs){
                if (count(explode('_', $parent)) > 1){
                    $parentItem = $this->getPermission($parent);
                }else{
                    $parentItem = $this->getRole($parent);
                }

                if (! $parentItem){
                    Console::updateProgress($i + 1 , $count);
                    $i++;
                    continue;
                }

                /**
                 * Звездочка означает что роль наследует все полномочия
                 */
                if ($childs == '*'){
                    $allRoles = $this->getRoles();
                    foreach ($allRoles as $role){
                        $this->addChild($parentItem, $role);
                    }

                    Console::updateProgress($i + 1 , $count);
                    $i++;
                    continue;
                }

                /**
                 * Если строка и она входит в массив стандартных полномочий добавляем в них все подобные полномочия
                 */
                if (! is_array( $childs ) && in_array($childs, PermissionEnum::all())){
                    $permissions = $db->createCommand("SELECT `name` FROM user_auth_item WHERE `name` LIKE '%{$childs}%' AND `type` = 2  GROUP BY `name`;")->queryAll();
                    if ($permissions){
                        foreach ($permissions as $p){
                            //исключить кастом
                            if (in_array($p, $customPermissions)) {
                                continue;
                            }

                            $item = $this->getPermission($p['name']);
                            if (! $item){
                                continue;
                            }

                            $this->addChild($parentItem, $item);
                        }
                    }

                    Console::updateProgress($i + 1 , $count);
                    $i++;

                    continue;
                }

                foreach ($childs as $child){
                    $childItem = $this->getPermission($child);
                    if (! $childItem){
                        $childItem = $this->getRole($child);
                        if (! $childItem) {
                            continue;
                        }
                    }

                    $this->addChild($parentItem, $childItem);
                }

                Console::updateProgress($i + 1 , $count);
                $i++;
            }

            $this->outputSuccess( "Rbac custom permissions generate start");
            $count = count($customPermissions);
            Console::startProgress(0, $count);
            $i = 0;
            foreach ($customPermissions as $key => $value){
                $access = $key;
                $accessConfig = [];
                if (filter_var($key, FILTER_VALIDATE_INT) !== false) {
                    $access = $value;
                }else{
                    $accessConfig = $value;
                }

                $permissionForm = $this->getPermissionForm($access, $accessConfig);
                if ($permissionForm){
                    //Добавялем правило (если есть)
                    $ruleClass = $this->getRuleClassFromConfig($access, $accessConfig);
                    if ($ruleClass){
                        $rule = $this->addRule($ruleClass);
                        $permissionForm->ruleName = $rule->name;
                    }

                    $permission = $this->addPermission($permissionForm);
                }

                Console::updateProgress($i + 1 , $count);
                $i++;
            }

            $this->outputSuccess( "Rbac custom roles generate start");
            $count = count($customRoles);
            Console::startProgress(0, $count);
            $i = 0;
            foreach ($customRoles as $key => $value){
                $access = $key;
                $accessConfig = [];
                if (filter_var($key, FILTER_VALIDATE_INT) !== false) {
                    $access = $value;
                }else{
                    $accessConfig = $value;
                }

                $roleForm = $this->getRoleForm($access, $accessConfig);
                if ($roleForm){
                    $role = $this->addRole($roleForm);
                }

                Console::updateProgress($i + 1 , $count);
                $i++;
            }

//            $transaction->commit();
            $this->getAuthManager()->invalidateCache();
        } catch (\Exception $e) {
//            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
//            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Возвращает форму роли
     *
     * @param $access ключ привелегии из конфига rbac
     * @param $config конфиг для привелегии из конфига
     * @return UserAuthRoleForm
     */
    public function getRoleForm($access, $config)
    {
        $form = new UserAuthRoleForm();
        $form->name = $this->getRoleByAccess($access);
        $form->description = $config['description'] ?? '';

        return $form;
    }

    /**
     * Врзвращает форму привелегии
     *
     * @param $access ключ привелегии из конфига rbac
     * @param $config конфиг для привелегии из конфига
     * @return UserAuthPermissionForm|null
     */
    public function getPermissionForm($access, $config)
    {
        if (! $this->getPermissionByAccess($access)){
            return null;
        }

        $form = new UserAuthPermissionForm();
        $form->name = $this->getPermissionByAccess($access);
        $form->description = $config['description'] ?? '';

        return $form;
    }

    /**
     * Возвращает правило из конфига
     *
     * @param $access
     * @param $config
     * @return string|null
     */
    public function getRuleClassFromConfig($access, $config)
    {
        if (! $this->getPermissionByAccess($access)){
            return null;
        }

        return $config['rule'] ?? null;
    }

    /**
     * Получить название роли по названию AccessEnum
     *
     * @param $access
     * @return string
     */
    public function getRoleByAccess($access)
    {
        $role = null;
        $array = explode("_", $access);

        return $array[0];
    }

    /**
     * Получить название полномочия по названию AccessEnum
     *
     * @param $permission
     * @return string
     */
    public function getPermissionByAccess($access)
    {
        $role = null;
        $array = explode("_", $access);

        return isset($array[1]) ?  $access : null;
    }
}