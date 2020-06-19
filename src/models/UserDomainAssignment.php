<?php

namespace concepture\yii2user\models;

use concepture\yii2logic\models\ActiveRecord;
use Yii;

/**
* This is the model class for table "user_domain_assignment".
*
* @property int $user_id
* @property int $domain_id
* @property string $access
* @property string $created_at
*/
class UserDomainAssignment extends ActiveRecord
{
    public $country_caption;

    /**
    * @see \concepture\yii2logic\models\ActiveRecord:label()
    *
    * @return string
    */
    public static function label()
    {
        return Yii::t('common', 'user_domain_assignment');
    }

    /**
    * @see \concepture\yii2logic\models\ActiveRecord:toString()
    * @return string
    */
    public function toString()
    {
        return $this->user_id;
    }

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'user_domain_assignment';
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
            [['user_id', 'domain_id', 'access'], 'required'],
            [['user_id', 'domain_id'], 'integer'],
            [['created_at'], 'safe'],
            [['access'], 'string', 'max' => 5],
            [['user_id', 'domain_id'], 'unique', 'targetAttribute' => ['user_id', 'domain_id']],
        ];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
    
            'user_id' => Yii::t('common', 'User ID'),
            'domain_id' => Yii::t('common', 'Domain ID'),
            'access' => Yii::t('common', 'Access'),
            'created_at' => Yii::t('common', 'Created At'),
    
        ];
    }
}
