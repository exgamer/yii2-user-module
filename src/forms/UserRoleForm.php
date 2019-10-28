<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * UserCredentialForm
 */
class UserRoleForm extends Form
{
    public $user_id;
    public $role_id;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'user_id',
                    'role_id'
                ],
                'required'
            ],
        ];
    }
}
