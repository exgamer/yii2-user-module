<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Model;

/**
 * Class UserAuthRoleForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAuthRoleForm extends Model
{
    public $name;
    public $description;

    public function rules()
    {
        return [
            [
                [
                    'name',
                ],
                'required'
            ],
            [
                [
                    'name',
                    'description',
                ],
                'string'
            ],
            ['name','match', 'pattern' => '/^[A-Z0-9_]+$/', 'message' => \Yii::t('common', 'Только заглавные латинские символы, цифры и подчеркивания')],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('common', 'Наименование'),
            'description' => Yii::t('common', 'Описание'),
        ];
    }
}
