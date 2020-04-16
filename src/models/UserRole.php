<?php
namespace concepture\yii2user\models;

use concepture\yii2user\enum\UserRoleEnum;
use concepture\yii2user\models\traits\UserTrait;
use Yii;
use concepture\yii2logic\models\ActiveRecord;


/**
 * @deprecated
 *
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
     * @see \concepture\yii2logic\models\ActiveRecord:label()
     *
     * @return string
     */
    public static function label()
    {
        return Yii::t('user', 'Роли пользователей');
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:toString()
     * @return string
     */
    public function toString()
    {
        return $this->role;
    }

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

    public function getRoleLabel()
    {
        return UserRoleEnum::label($this->role);
    }
}
