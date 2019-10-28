<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * UserCredentialForm
 */
class UserRoleHandbookForm extends Form
{
    public $caption;
    public $name;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'caption',
                    'name'
                ],
                'required'
            ],
        ];
    }
}
