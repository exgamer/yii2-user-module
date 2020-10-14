<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\services\traits\StatusTrait;
use concepture\yii2user\forms\UserSocialAuthForm;
use yii\db\ActiveQuery;
use concepture\yii2user\forms\UserEmailCredentialForm;
use concepture\yii2logic\forms\Model;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\services\Service;
use Yii;
use concepture\yii2user\traits\ServicesTrait;

/**
 * Class UserSocialAuthService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserSocialAuthService extends Service
{
    use ServicesTrait;

    /**
     * @param $client
     * @param $user_id
     * @return ActiveRecord
     */
    public function createByClient($client, $user_id)
    {
        $attributes = $client->getUserAttributes();
        $form = new UserSocialAuthForm();
        $form->user_id = $user_id;
        $form->source_id = (string) $client->getId();
        $form->source_user_id = (string) $attributes['id'];
        $form->source_name = $client->getName();
        $form->source_title = $client->getTitle();

        return $this->create($form);
    }
}
