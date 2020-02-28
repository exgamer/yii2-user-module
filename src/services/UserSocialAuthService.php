<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\services\traits\StatusTrait;
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
}
