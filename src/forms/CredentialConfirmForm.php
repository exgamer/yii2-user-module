<?php
namespace concepture\yii2user\forms;

use concepture\yii2logic\forms\Model;
use Yii;

/**
 * Форма подтверждения учетной записи
 *
 * Class CredentialConfirmForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class CredentialConfirmForm extends Model
{
    public $token;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'token'
                ],
                'required'
            ],
        ];
    }
}
