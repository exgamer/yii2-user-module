<?php

namespace concepture\yii2user\enum;

use Yii;
use concepture\yii2logic\enum\Enum;

/**
 * Виды операции с аккаунтами пользователей
 *
 * Class UserCredentialStatusEnum
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAccountOperationTypeEnum extends Enum
{
    const REFILL = 1;
    const WRITE_OFF = 0;

    public static function labels()
    {
        return [
            self::REFILL => Yii::t('user', "Пополнение"),
            self::WRITE_OFF => Yii::t('user', "Списание"),
        ];
    }
}
