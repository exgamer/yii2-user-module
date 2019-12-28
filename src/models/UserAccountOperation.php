<?php
namespace concepture\yii2user\models;

use Yii;
use concepture\yii2logic\models\ActiveRecord;

/**
 * Class UserAccountOperation
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAccountOperation extends ActiveRecord
{
    /**
     * @see \concepture\yii2logic\models\ActiveRecord:label()
     * @return string
     */
    public static function label()
    {
        return Yii::t('user', 'Операции по счетам');
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:toString()
     * @return string
     */
    public function toString()
    {
        return $this->sum;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_account_operation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'sum'
                ],
                'double'
            ],
            [
                [
                    'account_id',
                    'currency',
                    'type',
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
            'sum' => Yii::t('user', 'Сумма'),
            'account_id' => Yii::t('user', ' Аккаунт'),
            'type' => Yii::t('user', ' Вид операции'),
            'created_at' => Yii::t('user', 'Дата создания'),
        ];
    }
}
