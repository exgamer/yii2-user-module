<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;

class UserCredentialStatusEnum extends Enum
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const BLOCK = 1;
}
