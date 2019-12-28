<?php
namespace concepture\yii2user\models;

use Yii;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2user\models\traits\UserTrait;

/**
 * Class UserAccount
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAccount extends ActiveRecord
{
    use UserTrait;

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:label()
     * @return string
     */
    public static function label()
    {
        return Yii::t('user', 'Счета пользователей');
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:toString()
     * @return string
     */
    public function toString()
    {
        return $this->balance;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'balance'
                ],
                'double'
            ],
            [
                [
                    'user_id',
                    'currency',
                    'status'
                ],
                'integer'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'currency' => Yii::t('user', 'Валюта'),
            'balance' => Yii::t('user', 'Баланс'),
            'created_at' => Yii::t('user', 'Дата создания'),
        ];
    }
}
