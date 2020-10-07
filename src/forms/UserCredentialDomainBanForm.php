<?php

namespace concepture\yii2user\forms;

use yii\helpers\ArrayHelper;
use concepture\yii2logic\forms\Model;

/**
 * Class UserCredentialDomainBanForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialDomainBanForm extends Model
{
    public $domain_id;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'domain_id',
                ],
                'required',
            ],
            [
                [
                    'domain_id',
                ],
                'integer',
            ],
        ];
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function attributeLabels()
    {
        return [
            'domain_id' => \Yii::t('common', 'Домен'),
        ];
    }
}