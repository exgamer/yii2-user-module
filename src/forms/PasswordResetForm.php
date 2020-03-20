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
