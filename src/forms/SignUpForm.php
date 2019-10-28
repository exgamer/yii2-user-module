<?php
namespace concepture\yii2user\forms;

use concepture\yii2user\enum\UserCredentialTypeEnum;
use Yii;
use concepture\yii2logic\forms\Form;

/**
 * SignUpForm
 */
class SignUpForm extends Form
{
    public $username;
    public $identity;
    public $validation;
    public $credentialType= UserCredentialTypeEnum::EMAIL;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','identity', 'validation'], 'required'],
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
            ['username', 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 100],
            ['identity', 'email'],
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
            'username' => Yii::t('user', 'Имя пользователя')
        ];
    }
}
