<?php
namespace concepture\yii2user\models;

use Yii;
use concepture\yii2logic\models\ActiveRecord;

/**
 * UserRoleHandbook model
 *
 * @property integer $id
 * @property string $caption
 * @property string $name
 */
class UserRoleHandbook extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_role_handbook}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                    [
                        [
                            'caption',
                            'name',
                        ],
                        'string',
                        'min' => 2,
                        'max' => 100
                    ],
                    [
                        'name',
                        'match',
                        'pattern' => '/^[a-zA-Z0-9_-]+$/'
                    ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'caption' => Yii::t('user', 'Название'),
            'name' => Yii::t('user', 'Имя')
        ];
    }
}
