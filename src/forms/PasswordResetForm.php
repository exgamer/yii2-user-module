<?php
namespace concepture\yii2user\forms;

use concepture\yii2logic\forms\Model;
use Yii;

/**
 * Форма для смены пароля
 *
 * Class PasswordResetForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class PasswordResetForm extends Model
{
    public $token;
    public $validation;
    public $validationCompare;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'validation',
                    'validationCompare',
                    'token'
                ],
                'required'
            ],
            [
                [
                    'validation',
                    'validationCompare',
                ],
                'string',
                'min' => 6,
                'max'=>100
            ],
            ['validationCompare', 'compare','compareAttribute'=>'validation','operator'=>'==',
                'message'=> Yii::t('common', 'Пароли должны совпадать'), 'type' => 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'validation' => Yii::t('common', 'Новый пароль'),
            'validationCompare' => Yii::t('common', 'Подтвердите пароль')
        ];
    }
}
