<?php
namespace concepture\yii2user\models;

use Yii;
use concepture\yii2logic\models\ActiveRecord;


/**
 *Модель описывающая сущность для сбора email
 *
 * Class EmailHandbook
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class EmailHandbook extends ActiveRecord
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
                    'email'
                ],
                'email'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'email' => Yii::t('user', 'email')
        ];
    }
}
