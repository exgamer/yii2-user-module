<?php
namespace concepture\yii2user\models;

use Yii;
use concepture\yii2logic\models\ActiveRecord;

/**
 * UserRole model
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $role_id
 * @property datetime $created_at
 */
class UserRole extends ActiveRecord
{
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
                    'user_id',
                    'role_id'
                ],
                'integer'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'user_id' => Yii::t('user', 'Пользователь'),
            'role_id' => Yii::t('user', 'Роль'),
            'created_at' => Yii::t('user', 'Дата создания'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getRole()
    {
        return $this->hasOne(UserRoleHandbook::className(), ['id' => 'role_id']);
    }
}
