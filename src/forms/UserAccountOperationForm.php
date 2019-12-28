<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Class UserAccountOperationForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAccountOperationForm extends Form
{
    public $account_id;
    public $sum;
    public $type;
    public $currency;
    public $description;
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
                    'account_id',
                    'type',
                    'sum'
                ],
                'required'
            ],
        ];
    }
}
