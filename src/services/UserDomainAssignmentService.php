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
            'access' => $access,
        ]);
        if (! $model) {
            return true;
        }

        return $model->delete();
    }

    /**
     * Возвращает данные по доступу юзера к доменам
     * @param $userId
     * @return array
     */
    public function getAccessData($userId)
    {
        $data = $this->getStaticData('accessData-' . $userId);
        if ($data === null) {
            $data = $this->getAllByCondition([
                'user_id' => $userId,
            ]);
            $result =  [];
            if ($data) {
                foreach ($data as $d) {
                    $result[$d->domain_id][$d['access']] = $d;
                }
            }

            $data = $result;
            $this->setStaticData(function ($d) use ($data, $userId){
                $d['accessData-' . $userId] = $data;

                return $d;
            });
        }

        return $data;
    }
}
