<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2user\enum\UserCredentialTypeEnum;

/**
 * UserLoginCredentialForm
 */
class UserEmailCredentialForm extends UserCredentialForm
{
    /**
     * @see UserCredentialForm::formRules()
     */
    public function formRules()
    {
        return array_merge(
            [
                [
                    'type',
                    'default',
                    'value'=> UserCredentialTypeEnum::EMAIL
                ],
            ],
            parent::formRules()
        );
    }
}
