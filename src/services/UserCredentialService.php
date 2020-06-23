<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\services\traits\StatusTrait;
use yii\db\ActiveQuery;
use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\UserCredentialForm;
use concepture\yii2user\forms\UserEmailCredentialForm;
use concepture\yii2logic\forms\Model;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\services\Service;
use Yii;
use concepture\yii2user\traits\ServicesTrait;
use concepture\yii2handbook\services\traits\ReadSupportTrait as HandbookReadSupportTrait;

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
    use StatusTrait;
    use HandbookReadSupportTrait;

    /**
     * Дополнительные действия с моделью перед созданием
     * @param Form $form класс для работы
     */
    protected function beforeCreate(Model $form)
    {
        $form->validation = Yii::$app->security->generatePasswordHash($form->validation);
        parent::beforeCreate($form);
    }

    /**
     * Метод для расширения find()
     * !! ВНимание эти данные будут поставлены в find по умолчанию все всех случаях
     *
     * @param ActiveQuery $query
     * @see \concepture\yii2logic\services\Service::extendFindCondition()
     */
    protected function extendQuery(ActiveQuery $query)
    {
        $this->applyDomain($query);
    }

    /**
     * Создание учетки по емеилу
     *
     * @param $identity
     * @param $validation
     * @param $user_id
     * @param null $domain_id
     * @param int $status
     * @param int $generated
     * @return mixed
     */
    public function createEmailCredential($identity, $validation, $user_id, $domain_id = null, $status = UserCredentialStatusEnum::ACTIVE, $generated = 0)
    {
        $form = new UserCredentialForm();
        $form->identity = $identity;
        $form->validation = $validation;
        $form->status = $status;
        $form->user_id = $user_id;
        $form->domain_id = $domain_id;
        $form->generated = $generated;
        $form->type = UserCredentialTypeEnum::EMAIL;
        $result = $this->create($form);
        $this->emailHandbookService()->addEmail($identity);

        return $result;
    }

    /**
     * Возвращает запись по identity
     * @param $identity
     * @param int $type
     * @param int $status
     * @return ActiveRecord
     */
    public function findByIdentity($identity, $type = UserCredentialTypeEnum::EMAIL, $status = UserCredentialStatusEnum::ACTIVE)
    {
        return $this->getQuery()->andWhere(
            [
                'identity' => $identity,
                'type' => $type,
                'status' => $status,
            ]
        )->one();
    }

    /**
     * Возвращает учетку по email
     *
     * @param $email
     * @param null $status
     * @return mixed
     */
    public function findByEmail($email, $status = null)
    {
        $condition = [
            'identity' => strtolower($email),
            'type' => UserCredentialTypeEnum::EMAIL,
        ];
        if ($status) {
            $condition['status'] = $status;
        }

        return $this->getQuery()->resetCondition()->andWhere($condition)->one();
    }

    /**
     * Поиск модели по validation
     *
     * @param $validation
     * @return mixed
     */
    public function findByValidation($validation)
    {
        return $this->getQuery()->andWhere(
            [
                'validation' => $validation,
                'status' => UserCredentialStatusEnum::ACTIVE,
            ]
        )->one();
    }

    /**
     * Поиск записи для подтверждения учетки
     *
     * @param $validation
     * @return mixed
     */
    public function findCredentialConfirmToken($validation)
    {
        return $this->getQuery()->andWhere(
            [
                'validation' => $validation,
                'type' => UserCredentialTypeEnum::CREDENTIAL_CONFIRM_TOKEN,
                'status' => UserCredentialStatusEnum::ACTIVE,
            ]
        )->one();
    }

    /**
     * Возвращает запись для сброса пароля
     *
     * @param $validation
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findValidationResetToken($validation)
    {
        return $this->getQuery()->andWhere(
            [
                'validation' => $validation,
                'type' => UserCredentialTypeEnum::VALIDATION_RESET_TOKEN,
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
        return $this->getQuery()->andWhere(
            [
                'user_id' => $user_id,
                'type' => $type,
                'status' => UserCredentialStatusEnum::ACTIVE,
            ]
        )->one();
    }
}
