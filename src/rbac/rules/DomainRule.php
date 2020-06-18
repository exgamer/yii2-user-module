<?php
namespace concepture\yii2user\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Class DomainRule
 * @package concepture\yii2user\rbac\rules
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
abstract class DomainRule extends Rule
{
    protected function getAccess($user, $domain_id)
    {
        return Yii::$app->userDomainAssignmentService->getAccessData($user);
    }
}
