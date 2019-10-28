<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;

class UserCredentialTypeEnum extends Enum
{
    const LOGIN = 1;
    const EMAIL = 2;
    const AUTH_TOKEN = 3;
    const VALIDATION_RESET_TOKEN = 4;
}
