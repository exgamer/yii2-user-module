<?php

namespace concepture\yii2user\forms;

use yii\helpers\ArrayHelper;
use concepture\yii2logic\forms\Model;

/**
 * Class ChangePasswordForm
 *
 * Форма смены пароля
 *
 * @package frontend\forms
 * @author Poletaev Eugene <evgstn7@gmail.com>
 */
class ChangePasswordForm extends Model
{
    public $identity;
    public $old_password;
    public $new_password;
    public $repeat_password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'identity',
                ],
                'string',
            ],
            [
                [
                    'identity',
                    'old_password',
                    'new_password',
                    'repeat_password',
                ],
                'trim',
            ],
            [
                [
                    'old_password',
                    'new_password',
                    'repeat_password',
                ],
                'string',
                'max' => 255,
            ],
            ['old_password', 'compareOldPassword', 'on' => 'default'],
            ['repeat_password', 'compare', 'compareAttribute' => 'new_password'],
            [
                [
                    'old_password',
                    'new_password',
                    'repeat_password',
                ],
                'required',
                'when' => function($model) {
                    return (
                        !empty($model->old_password)
                        || !empty($model->new_password)
                        || !empty($model->repeat_password)
                    );
                },
            ],
            ['identity', 'filter', 'filter'=>'strtolower'],
        ];
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'identity' => \Yii::t('frontend', 'Email'),
            'old_password' => \Yii::t('frontend', 'Old password'),
            'new_password' => \Yii::t('frontend', 'New Password'),
            'repeat_password' => \Yii::t('frontend', 'Repeat password'),
        ]);
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function compareOldPassword($attribute, $params)
    {
        $credential = \Yii::$app->userCredentialService->findByIdentity($this->identity);
        if (!\Yii::$app->security->validatePassword($this->old_password, $credential->validation)) {
            $this->addError($attribute, \Yii::t('frontend', 'Old password is incorrect'));
        }
    }
}