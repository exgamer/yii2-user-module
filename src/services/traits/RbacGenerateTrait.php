<?php
namespace concepture\yii2user\services\traits;

use concepture\yii2logic\helpers\ClassHelper;
use concepture\yii2user\forms\UserAuthPermissionForm;
use concepture\yii2user\forms\UserAuthRoleForm;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Треит с методами для генерации ролей и полномочий для rbac
 *
 * Trait RbacGenerateTrait
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
trait RbacGenerateTrait
{
    /**
     * Возвращает массив классов контроллеров
     * из backend, пакетов concepture и kamaelkz
     *
     * @return array
     */
    protected function getControllerClasses()
    {
        $projectControllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@backend'), ['recursive' => true, 'only'=>['*Controller.php']]);
        $c = [];
        $conceptureControllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@vendor/concepture'), ['recursive' => true, 'only'=>['*Controller.php']]);
        $controllers = ArrayHelper::merge($projectControllers, $conceptureControllers);
        $kamaelControllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@vendor/kamaelkz'), ['recursive' => true, 'only'=>['*Controller.php']]);
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
        static $config = null;
        if (! empty($config)){
            return $config;
        }

        $baseConfig = require_once __DIR__ . '/../../config/rbac.php';
        $commonConfig = [];
        $commonConfigPath = Yii::getAlias('@common') . "/config/rbac.php";
        if (file_exists($commonConfigPath)) {
            $commonConfig = require_once $commonConfigPath;
        }

        $config = ArrayHelper::merge($baseConfig, $commonConfig);
        if (! isset($config['default_roles'])){
            throw new \Exception("default roles not set");
        }

        if (! isset($config['default_dependencies'])){
            throw new \Exception("default dependencies not set");
        }

        return $config;
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
        $excludedControllerNames = $defaultConfig['excluded_controller_names'];
        $controllerConfig = [];
        $access = [];
        $dependencies = [];
        foreach ($controllerNames as $name){
            if (array_search($name, $excludedControllerNames) !== false){
                continue;
            }

            foreach ($defaultRoles as $role){
                $roleName = $name . "_" . $role;
                $access[] = $roleName;
                if (isset($defaultDependencies[$role])){
                    foreach ($defaultDependencies[$role] as $dependentRole){
                        $dependencies[$roleName][] = $name . "_" . $dependentRole;
                    }

                }
            }
        }

        $controllerConfig['permissions'] = $access;
        $controllerConfig['dependencies'] = $dependencies;

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
     * генерация ролей и полномочий из конфигов
     */
    public function generate()
    {
        $db = $this->getAuthManager()->db;
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand("SET FOREIGN_KEY_CHECKS = 0; DELETE FROM user_auth_rule;")->execute();
            $db->createCommand("SET FOREIGN_KEY_CHECKS = 0; DELETE FROM user_auth_item_child;")->execute();
            $db->createCommand("SET FOREIGN_KEY_CHECKS = 0; DELETE FROM user_auth_item;")->execute();
            $config = $this->getAccessConfig();
            $accessData = $config['permissions'] ??[];
            $dependenciesData = $config['dependencies'] ??[];
            $newItems = [];
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

                $newItems[$access] = $access;
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
            }

            /**
             *  првоерка привязана ли роль которой нет в конфиге к юзерам
             */
            $roles = $db->createCommand("SELECT item_name FROM user_auth_assignment GROUP BY item_name;")->queryAll();
            $usedItems = ArrayHelper::map($roles, 'item_name', 'item_name');
            $diff = array_diff($usedItems, $newItems);
            if (! empty($diff)){
                throw new \Exception(implode(',' , $diff) . " these roles is used in user_auth_assignment, but not found in new rules");
            }

            /**
             * Добавляем зависимости полномочий
             */
            foreach ($dependenciesData as $parent => $childs){
                if (count(explode('_', $parent)) > 1){
                    $parentItem = $this->getPermission($parent);
                }else{
                    $parentItem = $this->getRole($parent);
                }

                if (! $parentItem){
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

                    continue;
                }

                foreach ($childs as $child){
                    $childItem = $this->getPermission($child);
                    if (! $childItem){
                        continue;
                    }

                    $this->addChild($parentItem, $childItem);
                }
            }

            $transaction->commit();
            $this->getAuthManager()->invalidateCache();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
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