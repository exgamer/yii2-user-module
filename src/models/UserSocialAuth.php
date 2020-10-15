<?php
namespace concepture\yii2user\models;

use concepture\yii2logic\models\traits\StatusTrait;
use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use Yii;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2handbook\models\traits\DomainTrait;
use concepture\yii2user\models\traits\UserTrait;

/**
 * Class UserSocialAuth
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserSocialAuth extends ActiveRecord
{
    use DomainTrait;
    use UserTrait;
    use StatusTrait;

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:label()
     * @return string
     */
    public static function label()
    {
        return Yii::t('common', 'Авторизационные данные пользователей черещ соц сети');
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:toString()
     * @return string
     */
    public function toString()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_social_auth}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'source_user_id',
                    'source_name',
                    'source_title',
                    'source_id',
                ],
                'string'
            ],
            [
                [
                    'user_id',
                ],
                'integer'
            ],


        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', '#'),
            'user_id' => Yii::t('common', 'Пользователь'),
            'created_at' => Yii::t('common', 'Дата создания'),
        ];
    }
}
