<?php
namespace concepture\yii2user\forms;

use concepture\yii2user\enum\UserCredentialTypeEnum;
use Yii;
use concepture\yii2logic\forms\Form;

/**
 * SignInForm
 */
class SignInForm extends Form
{
    public $username;
    public $identity;
    public $validation;
    public $rememberMe = true;
    public $restrictions = [];
    public $credentialType= UserCredentialTypeEnum::EMAIL;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identity', 'validation'], 'required'],
            [
                [
                    'validation'
                ],
                'string',
                'min' => 6,
                'max'=>100
            ],
            ['identity', 'trim'],
            ['validation', 'trim'],
            ['identity', 'email'],
            [
                [
                    'rememberMe'
                ], 'boolean'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'identity' => Yii::t('user', 'Адрес электронной почты'),
            'validation' => Yii::t('user', 'Пароль'),
            'rememberMe' => Yii::t(B'user', 'Запомнить меня'),
        ];
    }
}
