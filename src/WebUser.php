<?php
namespace concepture\yii2user;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\User;

/**
 * Класс описывающий атворизованного юзера
 *
 * Class WebUser
 * @package concepture\yii2user
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class WebUser extends User
{
    /**
     * Префикс для ключа в кеше для признака онлайн пользователь
     *
     * @var string
     */
    public static $isActivePrefix = 'user-online-';

    /**
     * @inheritDoc
     */
    protected function renewAuthStatus()
    {
        parent::renewAuthStatus();
        $identity = $this->getIdentity();
        if ($identity  && Yii::$app->has('cache')) {
            Yii::$app->cache->getOrSet(static::$isActivePrefix . $identity->id, function () use ($identity) {
                $identity->updateAttributes(['last_seen' => date('Y-m-d H:i:s')]);

                return 1;
            }, 300);
        }
    }

    public function isCurrentUser($user)
    {
        $identity = $this->getIdentity();
        if(! $identity) {

            return false;
        }

        if (! $user) {
            $user = $identity;
        }

        if (filter_var($user, FILTER_VALIDATE_INT) !== false) {
            if ($identity->id == $user) {
                return true;
            }

            return false;
        }

        if (  $identity->id == $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Проверка на общий доступ к домену
     * @param $domain_id
     * @return bool
     * @throws \Throwable
     */
    public function hasDomainAccess($domain_id = null)
    {
        if (! $domain_id) {
            $data = Yii::$app->domainService->getCurrentDomainData();
            $domain_id = $data['domain_id'] ?? null;
        }else{
            $data = Yii::$app->domainService->getDomainDataById($domain_id);
        }

        $alias = $data['alias'] ?? null;

        $result = false;
        $identity = $this->getIdentity();
        if(! $identity) {

            return $result;
        }

        static $accessData = [];
        if (isset($accessData[$domain_id])) {
            return $accessData[$domain_id];
        }

        static $roles = null;
        static $permissions = null;
        if ($roles === null) {
            $roles = Yii::$app->rbacService->getRolesByUser($identity->id);
        }

        foreach ($roles as $role => $data) {
            if ($this->can($role)){
                return true;
            }
        }

        if ($permissions === null) {
            $permissions = Yii::$app->rbacService->getPermissionsByUser($identity->id);
        }

        foreach ($permissions as $permission => $data) {
            $parts = explode('_', $permission);
            if (count($parts) == 3) {
                $parts[1] = strtoupper($alias);
                $permission = implode('_', $parts);
            }

            if ($this->can($permission)){
                return true;
            }
        }


        return $result;
    }

    /**
     * Признак доступа в административную панель
     *
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function canManageAdminPanel()
    {
        $identity = $this->getIdentity();
        if(null === $identity) {
            return false;
        }

        $user_id = $identity->getId();

        return Yii::$app->rbacService->hasRoleAssignment($user_id);
    }
}
