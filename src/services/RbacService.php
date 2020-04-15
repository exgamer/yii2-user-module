<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\helpers\ClassHelper;
use concepture\yii2user\forms\UserAuthPermissionForm;
use Yii;
use concepture\yii2user\forms\UserAuthRoleForm;
use concepture\yii2logic\services\Service;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\rbac\DbManager;
use concepture\yii2user\services\traits\RbacGenerateTrait;

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
     * @return DbManager
     */
    protected function getAuthManager()
    {
        return Yii::$app->authManager;
    }

    protected function getRoles()
    {
        return $this->getAuthManager()->getRoles();
    }

    protected function getPermission($name)
    {
        return $this->getAuthManager()->getPermission($name);
    }

    protected function getRole($name)
    {
        return $this->getAuthManager()->getRole($name);
    }

    /**
     * Привязка дочернего элемента к роли
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
     * @param UserAuthRolesForm $form
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
     * Удаление роли в rbac
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
     * @return
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
     * @return
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
}