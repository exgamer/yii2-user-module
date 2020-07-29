<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\helpers\ClassHelper;
use concepture\yii2logic\enum\AccessEnum;
use concepture\yii2user\forms\UserAuthPermissionForm;
use Yii;
use concepture\yii2user\forms\UserAuthRoleForm;
use concepture\yii2logic\services\Service;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\rbac\DbManager;
use concepture\yii2user\services\traits\RbacGenerateTrait;
use yii\rbac\Item;

/**
 * Сервис для rbac
 *
 * Class RbacService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class RbacService extends Service
{
    use RbacGenerateTrait;

    /**
     * Возвращает authManager
     *
     * @return DbManager
     */
    protected function getAuthManager()
    {
        return Yii::$app->authManager;
    }

    /**
     * назначение роли/полномочия пользователю
     *
     * @param $userId
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function assign($userId, $name)
    {
        $role = $this->getRole($name);
        if (! $role){
            $role = $this->getPermission($name);
        }

        if (! $role){
            throw new \Exception($name . " role or permission not found");
        }

        $result =  $this->getAuthManager()->assign($role, $userId);
        $this->getAuthManager()->invalidateCache();

        return $result;
    }

    /**
     * Забирает роль/полномочие у пользователя
     *
     * !!! Нельзя забрать роль/полномочие которое является наследником явно назваченной роли/полномочия
     *
     * @param $userId
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function revoke($userId, $name)
    {
        $role = $this->getRole($name);
        if (! $role){
            $role = $this->getPermission($name);
        }

        if (! $role){
            throw new \Exception($name . " role or permission not found");
        }

        $result =  $this->getAuthManager()->revoke($role, $userId);
        $this->getAuthManager()->invalidateCache();

        return $result;
    }

    /**
     * Возвращает роли системы
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->getAuthManager()->getRoles();
        $base = [];
        foreach ($roles as $key => $role){
            if (in_array($key, AccessEnum::all())){
                $base[$key] = $role;
                unset($roles[$key]);
            }
        }

        return $base + $roles;
    }

    /**
     * Возвращает полномочия системы
     * @return array
     */
    public function getPermissions()
    {
        return $this->getAuthManager()->getPermissions();
    }

    /**
     * Возвращает роли пользователя
     * @param $userId
     * @return array
     */
    public function getRolesByUser($userId)
    {
        return $this->getAuthManager()->getRolesByUser($userId);
    }

    /**
     * Возвращает полномочия пользователя
     * @param $userId
     * @return mixed
     */
    public function getPermissionsByUser($userId)
    {
        return $this->getAuthManager()->getPermissionsByUser($userId);
    }

    /**
     * Возвращает полномочие по имени
     * @param $name
     * @return Item
     */
    public function getPermission($name)
    {
        return $this->getAuthManager()->getPermission($name);
    }

    /**
     * Возвращает роль по имени
     * @param $name
     * @return Item
     */
    public function getRole($name)
    {
        return $this->getAuthManager()->getRole($name);
    }

    /**
     * Привязка дочернего элемента к роли/полномочию
     *
     * @param $parent
     * @param $child
     * @return bool
     * @throws \yii\base\Exception
     */
    public function addChild($parent, $child)
    {
        if (! $this->getAuthManager()->canAddChild($parent, $child) || $this->getAuthManager()->hasChild($parent, $child)){
            return false;
        }

        return $this->getAuthManager()->addChild($parent, $child);
    }

    /**
     * Добавление роли в rbac
     *
     * @param UserAuthRoleForm $form
     * @return
     */
    public function addRole(UserAuthRoleForm $form)
    {
        $role = $this->getAuthManager()->getRole($form->name);
        if ($role){

            return $role;
        }

        $role = $this->getAuthManager()->createRole($form->name);
        $role->description = $form->description;
        $this->getAuthManager()->add($role);

        return $role;
    }

    /**
     * Удаление роли из rbac
     *
     * @param string $name
     * @return
     */
    public function removeRole($name)
    {
        $role = $this->getAuthManager()->getRole($name);

        return $this->getAuthManager()->remove($role);
    }


    /**
     * Добавление полномочия в rbac
     *
     * @param UserAuthRolesForm $form
     * @return Item
     */
    public function addPermission(UserAuthPermissionForm $form)
    {
        $permission = $this->getAuthManager()->getPermission($form->name);
        if ($permission){
            return $permission;
        }

        $permission = $this->getAuthManager()->createPermission($form->name);
        $permission->description = $form->description;
        if ($form->ruleName) {
            $permission->ruleName = $form->ruleName;
        }

        $this->getAuthManager()->add($permission);

        return $permission;
    }

    /**
     * Удаление полномочия в rbac
     *
     * @param string $name
     * @return boolean
     */
    public function removePermission($name)
    {
        $permission = $this->getAuthManager()->getPermission($name);

        return $this->getAuthManager()->remove($permission);
    }

    /**
     * Добавил правило в rbac
     * @param $className
     * @return mixed|\yii\rbac\Rule|null
     * @throws \Exception
     */
    public function addRule($className)
    {
        $ruleObject = new $className();
        $rule = $this->getAuthManager()->getRule($ruleObject->name);
        if ($rule){
            return $rule;
        }


        $this->getAuthManager()->add($ruleObject);

        return $ruleObject;
    }

    /**
     * Прверка на наличие у пользователя назначенных ролей
     * @param $user_id
     * @return bool
     */
    public function hasRoleAssignment($user_id)
    {
        $sql = "SELECT count(*) FROM `user_auth_assignment` WHERE user_id = :USER_ID";
        $command = Yii::$app->getDb()->createCommand($sql);
        $command->bindParam(':USER_ID', $user_id);
        $count = $command->queryScalar() ?? 0;

        return $count > 0 ;
    }
}