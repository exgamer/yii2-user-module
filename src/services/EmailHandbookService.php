<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\services\Service;
use concepture\yii2user\forms\EmailHandbookForm;


/**
 * Class EmailHandbookService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class EmailHandbookService extends Service
{
    /**
     * Добавить в справочник адрес почты
     * @param $email
     * @return bool
     */
    public function addEmail($email)
    {
        $exist = $this->getOneByCondition(['email' => $email]);
        if ($exist){
            return true;
        }
        $form = new EmailHandbookForm();
        $form->email = $email;

        return $this->create($form);
    }
}
