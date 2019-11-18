<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m190629_123243_user_roles_table_create
 */
class m190629_123243_user_roles_table_create extends Migration
{
    function getTableName()
    {
        return 'user_role';
    }

    public function up()
    {
        $this->addTable([
            'id' => $this->bigPrimaryKey(),
            'user_id' =>  $this->bigInteger()->notNull(),
            'role_id' => $this->bigInteger()->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
        ]);
        $this->addUniqueIndex(
            ['user_id', 'role_id']);
        $this->addIndex(['role_id']);
        $this->addIndex(['user_id']);
        $this->addForeign('user_id', 'user','id');
        $this->addForeign('role_id', 'user_role_handbook','id');
    }
}
