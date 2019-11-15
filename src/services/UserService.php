<?php
namespace concepture\yii2user\services;

use Yii;
use concepture\yii2logic\forms\Form;
use concepture\yii2user\forms\UserForm;
use concepture\yii2logic\services\Service;
use concepture\yii2handbook\converters\LocaleConverter;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\services\traits\StatusTrait;

/**
 * Сервис содержит бизнес логику для работы с пользователем
 *
 * Class UserService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserService extends Service
{
    use StatusTrait;

    protected function beforeCreate(Form $form)
    {
        if (! $form->locale) {
            $form->locale = LocaleConverter::key(Yii::$app->language);
        }
    }

    /**
     * @param string $username
     * @param integer $locale
     * @return UserForm
     */
    public function createUser($username, $locale = null)
    {
        $form = new UserForm();
        $form->username = $username;
        $form->status = StatusEnum::ACTIVE;
        if ($locale){
            $form->locale = $locale;
        }

        return $this->create($form);
    }
}
