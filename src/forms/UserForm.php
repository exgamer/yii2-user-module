<?php

namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\forms\Form;
use concepture\yii2logic\validators\ModelValidator;
use kamaelkz\yii2cdnuploader\pojo\CdnImagePojo;

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
    public $website;
    public $famous = 0;

    public $social = [];

    /**
     * @see Form::formRules()
     */
    public function formRules()
    {
        return [
            [
                [
                    'username'
                ],
                'required'
            ],
            [
                [
                    'username',
                    'description',
                    'website',
                    'logo'
                ],
                'trim'
            ],
            [
                [
                    'logo',
                ],
                ModelValidator::class,
                'modelClass' => CdnImagePojo::class,
                'modifySource' => false
            ],
        ];
    }
}
