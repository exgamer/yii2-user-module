<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;

/**
 * Статусы авторизационных записей пользвоателя
 *
 * Class UserCredentialStatusEnum
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialStatusEnum extends Enum
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const BLOCK = 2;
}
