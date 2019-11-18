<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;
use Yii;
/**
 * Виды ролей пользователя
 *
 * Class UserRoleEnum
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleEnum extends Enum
{

    const ADMIN = "admin";
    const GUEST = "guest";


    public static function labels()
    {
        return [
            self::ADMIN => Yii::t('user', "Администратор"),
            self::GUEST => Yii::t('user', "Гость"),
        ];
    }
}
