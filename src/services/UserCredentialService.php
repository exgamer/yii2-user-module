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
        // Убрал потому что учетная запись едина для всех доменов
//        $this->applyDomain($query);
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
     * Создание учетки по телефонц
     *
     * @param $identity
     * @param $validation
     * @param $user_id
     * @param null $domain_id
     * @param int $status
     * @param int $generated
     * @return mixed
     */
    public function createPhoneCredential($identity, $validation, $user_id, $domain_id = null, $status = UserCredentialStatusEnum::ACTIVE, $generated = 0)
    {
        $form = new UserCredentialForm();
        $form->identity = $identity;
        $form->validation = $validation;
        $form->status = $status;
        $form->user_id = $user_id;
        $form->domain_id = $domain_id;
        $form->generated = $generated;
        $form->type = UserCredentialTypeEnum::PHONE;
        $result = $this->create($form);

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
     * Возвращает учетку по phone
     *
     * @param $phone
     * @param null $status
     * @return mixed
     */
    public function findByPhone($phone, $status = null)
    {
        $condition = [
            'identity' => $phone,
            'type' => UserCredentialTypeEnum::PHONE,
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

    /**
     * Смена email у учетки
     *
     * @param $user_id
     * @param $email
     * @param $new_email
     * @throws \Exception
     */
    public function changeCredentialEmail($user_id, $email, $new_email)
    {
        $credential = $this->findByEmail($new_email);
        if ($credential && $credential->user_id != $user_id) {
            throw new \Exception("email occupied");
        }

        $credential = $this->findByEmail($email);
        if (! $credential) {
            throw new \Exception("source credential not found");
        }

        if ($credential->type != UserCredentialTypeEnum::EMAIL) {
            throw new \Exception("source credential is not email type");
        }

        $credential->identity = $new_email;

        return $credential->save(false);
    }

    /**
     * активировать учетку емаил
     *
     * @param $user_id
     * @return bool
     */
    public function activateEmailCredentialByUserId($user_id)
    {
        $credential = $this->getOneByCondition([
            'user_id' => $user_id,
            'type' => UserCredentialTypeEnum::EMAIL,
        ]);

        if ($credential->status == UserCredentialStatusEnum::ACTIVE) {
            return true;
        }

        $credential->status = UserCredentialStatusEnum::ACTIVE;

        return $credential->save(false);
    }

    /**
     * Заблокировать учетные записи пользователя
     *
     * @param $user_id
     * @return bool
     */
    public function block($user_id)
    {
        $this->updateAllByCondition(
            [
                'status' => UserCredentialStatusEnum::BLOCK
            ],
            [
                'user_id' => $user_id,
            ]
        );

        return true;
    }

    /**
     * Заблокировать учетные записи пользователя для домена
     *
     * @param integer $user_id
     * @param integer $domain_id
     * @return bool
     */
    public function banDomain($user_id, $domain_id = null)
    {
        return $this->setBannedDomain($user_id, 1, $domain_id);
    }

    /**
     * Разбанить учетные записи пользователя для домена
     *
     * @param $user_id
     * @param null $domain_id
     * @return bool
     */
    public function unbanDomain($user_id, $domain_id = null)
    {
        return $this->setBannedDomain($user_id, 0, $domain_id);
    }

    /**
     * усановить блокированные домены
     *
     * @param integer $user_id
     * @param integer $action // забанить = 1, разбанить = 0
     * @param integer $domain_id
     * @return bool
     */
    public function setBannedDomain($user_id, $action = 1, $domain_id = null)
    {
        if (! $domain_id) {
            $domain_id = Yii::$app->domainService->getCurrentDomainId();
        }

        $models = $this->getAllByCondition(function (\concepture\yii2logic\db\ActiveQuery $query) use($user_id) {
            $query->resetCondition();
            $query->andWhere(['user_id' => $user_id]);
            $query->andWhere(['type' => [UserCredentialTypeEnum::EMAIL, UserCredentialTypeEnum::PHONE]]);
        });

        foreach ($models as $model) {
            $domains = $model->banned_domains ?? [];
            // Забанить домен
            if ($action == 1) {
                $domains[] = $domain_id;
                $domains = array_unique($domains);
            }

            // разбанить домен
            if ($action == 0) {
                if(($key = array_search($domain_id, $domains)) !== false){
                    unset($domains[$key]);
                }

                $domains = array_values($domains);
            }

            $this->updateByModel($model, ['banned_domains' => $domains]);
        }

        return true;
    }

    /**
     * @param int $user_id
     * @return bool
     */
    public function emailCredentialDomainIsBanned($user_id)
    {
        $credential = $this->getOneByCondition([
            'user_id' => $user_id,
            'type' => UserCredentialTypeEnum::EMAIL,
        ]);
        if ($credential->banned_domains && is_array($credential->banned_domains)) {
            return in_array(Yii::$app->domainService->getCurrentDomainId(), $credential->banned_domains);
        }
        return false;
    }
}
