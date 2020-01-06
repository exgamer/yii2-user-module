<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191126_102343__user_fix
 */
class m191126_102343__user_fix extends Migration
{
    public function getTableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createColumn("logo", $this->string(1024));
        $this->createColumn("description", $this->string(1024));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191108_102328__user_fix cannot be reverted.\n";

        return false;
    }
}
