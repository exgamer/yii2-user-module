<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Class UserAccountForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAccountForm extends Form
{
    public $user_id;
    public $balance;
    public $currency;
    public $status;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'currency',
                    'user_id'
                ],
                'required'
            ],
        ];
    }
}
