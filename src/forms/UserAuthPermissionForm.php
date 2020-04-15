<?php
namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\forms\Model;

/**
 * Class UserAuthPermissionForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAuthPermissionForm extends Model
{
    public $name;
    public $ruleName;
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
                    'ruleName',
                ],
                'string'
            ],
            ['name','match', 'pattern' => '/^[A-Z0-9_]+$/', 'message' => \Yii::t('yii2admin', 'Только заглавные латинские символы, цифры и подчеркивания')],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('yii2admin', 'Наименование'),
            'description' => Yii::t('yii2admin', 'Описание'),
        ];
    }
}
