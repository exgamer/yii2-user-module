<?php
namespace concepture\yii2user\services;

use concepture\yii2logic\models\ActiveRecord;
use concepture\yii2logic\services\Service;
use concepture\yii2logic\services\traits\StatusTrait;
use concepture\yii2user\enum\UserAccountOperationTypeEnum;
use concepture\yii2user\forms\UserAccountForm;
use concepture\yii2user\forms\UserAccountOperationForm;
use concepture\yii2user\traits\ServicesTrait;
use Exception;

/**
 * Class UserAccountOperationService
 * @package concepture\yii2user\services
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAccountOperationService extends Service
{
    use StatusTrat;
    use ServicesTrait;

    /**
     * Пополнение счета
     *
     * @param integer $user_id
     * @param double $sum
     * @param integer $currency
     * @param string $description
     * @return ActiveRecord
     */
    public function refill($user_id, $sum, $currency, $description = null)
    {
        $account = $this->userAccountOperationService()->getOneByCondition([
            'user_id' => $user_id,
            'currency' => $currency,
        ]);
        if (! $account){
            $accountForm = new UserAccountForm();
            $accountForm->user_id = $user_id;
            $accountForm->currency = $currency;
            $accountForm->status = 1;
            $account = $this->userAccountService()->create($accountForm);
        }

        $form = new UserAccountOperationForm();
        $form->type = UserAccountOperationTypeEnum::REFILL;
        $form->currency = $account->currency;
        $form->sum = $sum;
        $form->status = 1;
        $form->account_id = $account->id;
        if ($description){
            $form->description = $description;
        }

        return $this->userAccountOperationService()->create($form);
    }

    /**
     * Снятие со счета
     *
     * @param integer $user_id
     * @param double $sum
     * @param integer $currency
     * @param string $description
     * @return ActiveRecord
     * @throws Exception
     */
    public function writeOff($user_id, $sum, $currency, $description = null)
    {
        $account = $this->userAccountOperationService()->getOneByCondition([
            'user_id' => $user_id,
            'currency' => $currency,
        ]);
        if (! $account){
            throw new Exception("account not exists");
        }

        if ($account->balance < $sum){
            throw new Exception("not enough balance");
        }

        $form = new UserAccountOperationForm();
        $form->type = UserAccountOperationTypeEnum::WRITE_OFF;
        $form->currency = $account->currency;
        $form->sum = $sum;
        $form->status = 1;
        $form->account_id = $account->id;
        if ($description){
            $form->description = $description;
        }

        return $this->userAccountOperationService()->create($form);
    }
}
