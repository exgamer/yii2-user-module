<?php
namespace concepture\yii2user\forms;

use concepture\yii2logic\forms\Form;
use Yii;

/**
 * Password reset form
 */
class PasswordResetForm extends Form
{
    public $token;
    public $validation;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'validation',
                    'token'
                ],
                'required'
            ],
            [
                [
                    'validation'
                ],
                'string',
                'min' => 6,
                'max'=>100
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'validation' => Yii::t('user', 'Введите новый пароль')
        ];
    }
}
