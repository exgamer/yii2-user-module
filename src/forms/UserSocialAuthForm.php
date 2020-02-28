<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Class UserSocialAuthForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserSocialAuthForm extends Form
{
    public $user_id;
    public $source_user_id;
    public $source_name;
    public $source_title;
    public $source_id;

    /**
     * @see CForm::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'user_id',
                    'source_user_id',
                    'source_name',
                    'source_title',
                    'source_id',
                ],
                'required'
            ],
        ];
    }
}
