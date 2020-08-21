<?php
namespace concepture\yii2user\services;

use Yii;
use yii\db\ActiveQuery;
use concepture\yii2logic\forms\Model;
use concepture\yii2user\forms\UserForm;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\services\Service;
use concepture\yii2handbook\converters\LocaleConverter;
use concepture\yii2logic\services\traits\StatusTrait;
use concepture\yii2handbook\services\traits\ModifySupportTrait as HandbookModifySupportTrait;
use concepture\yii2handbook\services\traits\ReadSupportTrait as HandbookReadSupportTrait;

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
    use HandbookModifySupportTrait;
    use HandbookReadSupportTrait;

    protected function beforeCreate(Model $form)
    {
        $this->setCurrentLocale($form);
        parent::beforeCreate($form);
    }

    /**
     * @param $entity
     * @return UserForm
     */
    public function createUserFromForm($entity)
    {
        if (is_object($entity)) {
            $form = Yii::createObject( UserForm::class);
            $form->setAttributes($entity->attributes);
            $form->status = StatusEnum::ACTIVE;

            return $this->create($form);
        }

        return $this->createUser($entity);
    }

    /**
     * @param string $username
     * @param integer $locale
     * @return UserForm
     */
    public function createUser($username, $locale = null)
    {
        $form = Yii::createObject( UserForm::class);
        $form->username = $username;
        $form->status = StatusEnum::ACTIVE;
        if ($locale){
            $form->locale = $locale;
        }

        return $this->create($form);
    }
}
