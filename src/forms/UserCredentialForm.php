<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Форма дял сущности авторизационных данных пользователя
 *
 * Class UserCredentialForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialForm extends Form
{
    public $user_id;
    public $identity;
    public $validation;
    public $parent_id;
    public $domain_id;
    public $type;
    public $status;
    public $generated = 0;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'validation',
                    'type',
                    'user_id'
                ],
                'required'
            ],
        ];
    }
}
