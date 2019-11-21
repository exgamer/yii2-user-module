<?php
namespace concepture\yii2user\models;

use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use Yii;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\enum\RegexPatternEnum;

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
            ['identity', 'match', 'pattern' => RegexPatternEnum::DIGITS_UNDERSCORE_LETTERS],
            ['validation', 'string'],
            [
                [
                    'user_id',
                    'type',
                    'status',
                    'parent_id',
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
                    'user_id',
                    'identity',
                    'type'
                ],
                'unique',
                'targetAttribute' => ['user_id', 'identity', 'type']
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
            'identity' => Yii::t('user', 'Логин'),
            'created_at' => Yii::t('user', 'Дата создания'),
            'updated_at' => Yii::t('user', 'Дата обновления')
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
