<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;
use Yii;
/**
 * @deprecated
 *
 * Виды ролей пользователя
 *
 * Class UserRoleEnum
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleEnum extends Enum
{

    const ADMIN = "admin";
    const SUPER_ADMIN = "super_admin";
    const GUEST = "guest";


    public static function labels()
    {
        return [
            self::ADMIN => Yii::t('common', "Администратор"),
            self::SUPER_ADMIN => Yii::t('common', "Супер администратор"),
            self::GUEST => Yii::t('common', "Гость"),
        ];
    }
}
