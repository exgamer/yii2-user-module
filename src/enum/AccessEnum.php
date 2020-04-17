<?php

namespace concepture\yii2user\enum;

use concepture\yii2logic\enum\Enum;
use Yii;

/**
 * Константы ролей и полномочий для доступа
 *
 * Class AccessEnum
 * @package concepture\yii2user\enum
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class AccessEnum extends Enum
{
    /**
     * Роль которая имеет абсолютно все права
     */
    const SUPERADMIN = "SUPERADMIN";

    /**
     * Роль добавлена для обратной совместимости с прошлой версией
     */
    const ADMIN = "admin";
    const EDITOR = "EDITOR";
    const READER = "READER";
    const STAFF = "STAFF";
}
