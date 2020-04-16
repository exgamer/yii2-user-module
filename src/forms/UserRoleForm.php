<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * @deprecated
 * Форма для сущности ролей пользователя
 *
 * Class UserRoleForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleForm extends Form
{
    public $user_id;
    public $role;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'user_id',
                    'role'
                ],
                'required'
            ],
        ];
    }
}
