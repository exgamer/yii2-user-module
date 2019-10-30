<?php
namespace concepture\yii2user\services;

use concepture\yii2user\enum\UserCredentialStatusEnum;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\forms\UserCredentialForm;
use concepture\yii2user\forms\UserEmailCredentialForm;
use concepture\yii2logic\forms\Form;
use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\services\Service;
use Yii;

/**
 * UserCredentialService
 *
 */
class UserCredentialService extends Service
{
    protected function beforeCreate(Form $form)
    {
        $form->validation = Yii::$app->security->generatePasswordHash($form->validation);
    }

    public function createEmailCredential($identity, $validation, $user_id)
    {
        $form = new UserCredentialForm();
        $form->identity = $identity;
        $form->validation = $validation;
        $form->user_id = $user_id;
        $form->type = UserCredentialTypeEnum::EMAIL;

        return $this->create($form);
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
