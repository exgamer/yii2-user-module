<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\services\traits\StatusTrait;
use concepture\yii2user\forms\UserSocialAuthForm;
use yii\db\ActiveQuery;
use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\UserCredentialForm;
use concepture\yii2user\forms\UserEmailCredentialForm;
use concepture\yii2logic\forms\Model;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\services\Service;
use Yii;
use concepture\yii2user\traits\ServicesTrait;
use concepture\yii2handbook\services\traits\ReadSupportTrait as HandbookReadSupportTrait;

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
        $form->source_id = $client->getId();
        $form->source_user_id = $attributes['id'];
        $form->source_name = $client->defaultName();
        $form->source_title = $client->defaultTitle();

        return $this->create($form);
    }
}
