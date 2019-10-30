<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Форма для сущности справочника ролей пользователя
 *
 * Class UserRoleHandbookForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
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
