<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;

/**
 * Виды авторизационных записей пользователя
 *
 * Class UserCredentialTypeEnum
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialTypeEnum extends Enum
{
    const LOGIN = 1;
    const EMAIL = 2;
    const AUTH_TOKEN = 3;
    const VALIDATION_RESET_TOKEN = 4;
    const CREDENTIAL_CONFIRM_TOKEN = 5;
}
