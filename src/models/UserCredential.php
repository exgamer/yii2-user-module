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
 * Модель авторизационных данных пользователя
 *
 * Class UserCredential
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $status
 * @property string $identity
 * @property string $validation
 * @property datetime $created_at
 * @property datetime $updated_at
 *
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredential extends ActiveRecord
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
        return Yii::t('user', 'Авторизационные данные пользователей');
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:toString()
     * @return string
     */
    public function toString()
    {
        return $this->identity;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_credential}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['identity', 'string', 'min' => 2, 'max' => 255],
            ['validation', 'string'],
            [
                [
                    'user_id',
                    'type',
                    'status',
                    'parent_id',
                    'domain_id',
                ],
                'integer'
            ],
            [
                'status',
                'default',
                'value'=> UserCredentialStatusEnum::ACTIVE
            ],
            ['status', 'in', 'range' => UserCredentialStatusEnum::all()],
            ['type', 'in', 'range' => UserCredentialTypeEnum::all()],
            [
                [
                    'identity'
                ],
                'unique',
                'targetAttribute' => ['identity', 'type']
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'type' => Yii::t('user', 'Тип записи'),
            'validation' => Yii::t('user', 'Пароль'),
            'user_id' => Yii::t('user', 'Пользователь'),
            'domain_id' => Yii::t('user', 'Домен'),
            'identity' => Yii::t('user', 'Логин'),
            'status' => Yii::t('user', 'Статус'),
            'created_at' => Yii::t('user', 'Дата создания'),
            'updated_at' => Yii::t('user', 'Дата обновления')
        ];
    }
}
