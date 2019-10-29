<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Password reset request form
 */
class EmailPasswordResetRequestForm extends Form
{
    public $identity;
    public $route = 'site/reset-password';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['identity', 'trim'],
            ['identity', 'required'],
            ['identity', 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'identity' => Yii::t('user', 'Адрес электронной почты')
        ];
    }
}
