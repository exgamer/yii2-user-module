<?php
namespace concepture\yii2user\services;

use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\UserCredentialForm;
use concepture\yii2user\forms\UserEmailCredentialForm;
use concepture\yii2logic\forms\Model;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\services\Service;
use Yii;
use concepture\yii2user\traits\ServicesTrait;

/**
 * Сервис содержит бизнес логику дял работы с авторизационными записями пользователя
 *
 * Class UserCredentialService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialService extends Service
{
    use ServicesTrait;

    /**
     * Дополнительные действия с моделью перед созданием
     * @param Form $form класс для работы
     */
    protected function beforeCreate(Model $form)
    {
        $form->validation = Yii::$app->security->generatePasswordHash($form->validation);
    }

    /**
     * Создание учетки по емеилу
     *
     * @param $identity
     * @param $validation
     * @param $user_id
     * @return mixed
     */
    public function createEmailCredential($identity, $validation, $user_id)
    {
        $form = new UserCredentialForm();
        $form->identity = $identity;
        $form->validation = $validation;
        $form->user_id = $user_id;
        $form->type = UserCredentialTypeEnum::EMAIL;
        $result = $this->create($form);
        $this->emailHandbookService()->addEmail($identity);

        return $result;
    }

    /**
     * Возвращает запись по identity
     * @param $identity
     * @param $type
     * @return ActiveRecord
     */
    public function findByIdentity($identity, $type = UserCredentialTypeEnum::EMAIL)
    {
        return $this->getQuery()->where(
            [
                'identity' => $identity,
                'type' => $type,
                'status' => UserCredentialStatusEnum::ACTIVE,
            ]
        )->one();
    }

    /**
     * Поиск модели по validation
     *
     * @param $validation
     * @return mixed
     */
    public function findByValidation($validation)
    {
        return $this->getQuery()->where(
            [
                'validation' => $validation,
                'status' => UserCredentialStatusEnum::ACTIVE,
            ]
        )->one();
    }

    /**
     * Возвращает запись по $type $user_id
     * @param $user_id
     * @param $type
     * @return ActiveRecord
     */
    public function findByType($user_id, $type)
    {
        return $this->getQuery()->where(
            [
                'user_id' => $user_id,
                'type' => $type,
                'status' => UserCredentialStatusEnum::ACTIVE,
            ]
        )->one();
    }
}
