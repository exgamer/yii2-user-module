<?php
namespace concepture\yii2user\rbac\rules;

use concepture\yii2logic\enum\AccessTypeEnum;
use concepture\yii2logic\helpers\AccessHelper;
use Yii;
use yii\rbac\Rule;

/**
 * Class DomainRule
 * @package concepture\yii2user\rbac\rules
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class DomainRule extends Rule
{
    public $name = 'domain-rule';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $domainId = $params['domain_id'] ?? null;
        if (! $domainId) {
            $domainId = Yii::$app->domainService->getCurrentDomainId();
        }

        $access = $this->getAccess($user, $domainId);
        $domainAssignments = $access[$domainId] ?? null;
        if ($domainAssignments === null) {
            return false;
        }

        if (! isset($params['action'])) {
            return false;
        }

        $actions = $params['action'];
        if (! is_array($actions)) {
            $actions = [$actions];
        }

        if (array_intersect($actions, AccessHelper::$_read_actions) && isset($domainAssignments[AccessTypeEnum::READ])) {
            return true;
        }

        if (array_intersect($actions, AccessHelper::$_edit_actions) && isset($domainAssignments[AccessTypeEnum::WRITE])) {
            return true;
        }

        if (array_intersect($actions, AccessHelper::$_sort_actions) && isset($domainAssignments[AccessTypeEnum::WRITE])) {
            return true;
        }

        return false;
    }

    protected function getAccess($user, $domain_id)
    {
        return Yii::$app->userDomainAssignmentService->getAccessData($user);
    }
}
