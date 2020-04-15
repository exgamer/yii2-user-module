<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;
use Yii;

/**
 * Основные базовые полномочия
 *
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class PermissionEnum extends Enum
{
    const ADMIN = "ADMIN";
    const EDITOR = "EDITOR";
    const READER = "READER";
    const STAFF = "STAFF";
}