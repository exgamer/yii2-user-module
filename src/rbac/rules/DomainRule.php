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
        static $access = null;
        if (! $access) {
            $access = Yii::$app->userDomainAssignmentService->getAllByCondition([
                'user_id' => $user,
                'domain_id' => $domain_id
            ]);
            if (! $access) {
                $access = [];
            }

            $access = \yii\helpers\ArrayHelper::index($access, 'access');
        }

        return $access;
    }
}
