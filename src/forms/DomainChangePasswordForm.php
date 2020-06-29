<?php

namespace concepture\yii2user\forms;

use Yii;
use concepture\yii2logic\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use concepture\yii2logic\forms\Model;
use yii\helpers\Json;

/**
 * Форма смены пароля для возможности существования у однйо учетки паролей отдельно для каждого домена
 *
 * Class DomainChangePasswordForm
 * @package concepture\yii2user\forms
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class DomainChangePasswordForm extends ChangePasswordForm
{
    /**
     * @param $attribute
     * @param $params
     */
    public function compareOldPassword($attribute, $params)
    {
        $credential = \Yii::$app->userCredentialService->findByIdentity($this->identity);
        $validation = $credential->validation;
        /**
         * Если паролей много ищем для текущего домена
         */
        if (StringHelper::isJson($credential->validation)) {
            $validationArray = Json::decode($credential->validation);
            $validation = $validationArray[Yii::$app->domainService->getCurrentDomainId()] ?? null;
        }

        if (! $validation) {
            $this->addError($attribute, \Yii::t('common', 'Old password is incorrect'));
        }

        if (!\Yii::$app->security->validatePassword($this->old_password, $validation)) {
            $this->addError($attribute, \Yii::t('common', 'Old password is incorrect'));
        }
    }
}