<?php
namespace concepture\yii2user\forms;

use concepture\yii2logic\enum\StatusEnum;
use Yii;
use concepture\yii2logic\forms\Form;

/**
 * Форма для сущности пользователя
 *
 * Class UserForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserForm extends Form
{
    public $username;
    public $locale;
    public $status = StatusEnum::ACTIVE;
    public $logo;
    public $description;

    /**
     * @see Form::formRules()
     */
    public function formRules()
    {
        return [
            [['username'], 'required'],
            ['username', 'trim'],
        ];
    }
}
