<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Форма для запроса ссылки на смену пароля
 *
 * Class EmailPasswordResetRequestForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
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
