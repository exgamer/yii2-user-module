<?php

namespace concepture\yii2user\services;

use concepture\yii2logic\enum\AccessTypeEnum;
use concepture\yii2logic\services\Service;

/**
 * Class UserDomainAssignmentService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserDomainAssignmentService extends Service
{
    /**
     * Назначить доступ
     *
     * @param $userId
     * @param $domainId
     * @param $access
     * @return mixed
     * @throws \Exception
     */
    public function assign($userId, $domainId, $access)
    {
        $data = [
            'user_id' => $userId,
            'domain_id' => $domainId,
            'access' => $access,
        ];

        return $this->batchInsert(
            array_keys($data),
            [$data]
        );
    }

    /**
     * Забрать доступ
     *
     * @param $userId
     * @param $domainId
     * @param $access
     * @return mixed
     * @throws \Exception
     */
    public function revoke($userId, $domainId, $access)
    {
        $model = $this->getOneByCondition([
            'user_id' => $userId,
            'domain_id' => $domainId,
        ]);
        if (! $model) {
            return true;
        }

        switch ($access) {
            case AccessTypeEnum::READ_WRITE;
            case AccessTypeEnum::WRITE;
                $access = AccessTypeEnum::READ;
                break;
            default:
                $access = null;
        }

        if (! $access) {
            return $model->delete();
        }


        $data = [
            'user_id' => $userId,
            'domain_id' => $domainId,
            'access' => $access,
        ];

        return $this->batchInsert(
            array_keys($data),
            [$data]
        );
    }
}
