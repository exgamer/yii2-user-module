<?php

namespace concepture\yii2user\forms;

use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\validators\ModelValidator;
use kamaelkz\yii2cdnuploader\pojo\CdnImagePojo;
use kamaelkz\yii2cdnuploader\validators\ResourceValidator;

/**
 * Форма для сущности пользователя
 *
 * Class UserForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserForm extends \kamaelkz\yii2admin\v1\forms\BaseForm
{
    public $username;
    public $last_name;
    public $first_name;
    public $locale;
    public $status = StatusEnum::ACTIVE;
    public $logo;
    public $description;
    public $website;
    public $famous = 0;
    public $domain_id;

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
                    'last_name',
                    'first_name',
                    'description',
                    'website',
                    'logo'
                ],
                'trim'
            ],
            [
                [
                    'domain_id',
                ],
                'integer'
            ],
            [
                [
                    'logo',
                ],
                ResourceValidator::class,
            ],
        ];
    }
}
