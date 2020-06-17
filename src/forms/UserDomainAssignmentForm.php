<?php

namespace concepture\yii2user\forms;


use kamaelkz\yii2admin\v1\forms\BaseForm;

/**
* This is the form class for model "concepture\yii2user\models\UserDomainAssignment".
*
* @property int $user_id
* @property int $domain_id
* @property string $access
*/
class UserDomainAssignmentForm extends BaseForm
{
    
    public $user_id;
    public $domain_id;
    public $access;
    
    /**
    * {@inheritdoc}
    */
    public function formRules()
    {
        return [
            [['user_id', 'domain_id', 'access'], 'required']
        ];
    }
}
