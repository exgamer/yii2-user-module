<?php
use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m190629_113602_user_credentials_table_create
 */
class m190629_113602_user_credentials_table_create extends Migration
{
    public function getTableName()
    {
        return 'user_credential';
    }

    public function up()
    {
        $this->addTable([
            'id' => $this->primaryKey(),
            'user_id' =>  $this->integer(),
            'identity' => $this->string(255),
            'validation' => $this->string(512),
            'parent_id' => $this->integer(),
            'type' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
            'updated_at' => $this->dateTime()->append('ON UPDATE NOW()'),
        ]);
        $this->addUniqueIndex(
            ['user_id', 'identity', 'type']);
        $this->addIndex(['type']);
        $this->addIndex(['status']);
        $this->addIndex(['user_id']);
        $this->addIndex(['identity']);
        $this->addIndex(['parent_id']);
    }
}
