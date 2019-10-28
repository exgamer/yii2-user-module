<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m190629_123851_user_roles_handbook_table_create
 */
class m190629_123851_user_roles_handbook_table_create extends Migration
{
    function getTableName()
    {
        return 'user_role_handbook';
    }

    public function up()
    {
        $this->addTable([
            'id' => $this->primaryKey(),
            'caption' => $this->text(),
            'name' => $this->text(),
        ]);
        $this->addUniqueIndex(['name']);
    }
}
