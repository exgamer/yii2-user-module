<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Model;

/**
 * Форма для запроса ссылки на смену пароля
 *
 * Class EmailPasswordResetRequestForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class EmailPasswordResetRequestForm extends Model
{
    public $identity;
    public $route = 'site/reset-password';
    public $mailTmpPath = "@concepture/yii2user/views/mailer/password_reset_html";
    public $sendMail = true;
    public $token;

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
