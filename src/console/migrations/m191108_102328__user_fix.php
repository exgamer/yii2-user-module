<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191108_102328__user_fix
 */
class m191108_102328__user_fix extends Migration
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
        $this->removeColumn("domain_id");
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
