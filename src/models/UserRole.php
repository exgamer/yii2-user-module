<?php
namespace concepture\yii2user\models;

use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2user\models\traits\UserTrait;
use Yii;
use concepture\yii2logic\models\ActiveRecord;


/**
 * Модель ролей пользователя
 *
 * Class UserRole
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $role
 * @property datetime $created_at
 *
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRole extends ActiveRecord
{
    use UserTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'user_id'
                ],
                'integer'
            ],
            [
                [
                    'role'
                ],
                'string',
                'max'=>50
            ],
            [
                [
                    'user_id',
                    'role'
                ],
                'unique',
                'targetAttribute' => ['user_id', 'role']
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'user_id' => Yii::t('user', 'Пользователь'),
            'role' => Yii::t('user', 'Роль'),
            'created_at' => Yii::t('user', 'Дата создания'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getRoleLabel()
    {
        return UserRoleEnum::label($this->role);
    }
}
