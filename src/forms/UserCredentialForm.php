<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * UserCredentialForm
 */
class UserCredentialForm extends Form
{
    public $user_id;
    public $identity;
    public $validation;
    public $parent_id;
    public $type;
    public $status;

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
