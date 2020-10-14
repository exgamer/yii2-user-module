<?php
namespace concepture\yii2user\forms;

use concepture\yii2user\enum\UserCredentialTypeEnum;

/**
 * Class EmailCredentialForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class EmailCredentialForm extends UserCredentialForm
{
    public $type = UserCredentialTypeEnum::EMAIL;

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
                    'user_id',
                    'identity'
                ],
                'required'
            ],
            [
                'identity',
                'email'
            ]
        ];
    }
}
