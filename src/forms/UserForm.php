<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * UserForm
 */
class UserForm extends Form
{
    public $username;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [['username'], 'required'],
            ['username', 'trim'],
        ];
    }
}
