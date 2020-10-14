<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200816_090600_user_fix
 */
class m200816_090600_user_fix extends Migration
{
    /**
     * @inheritDoc
     */
    public function getTableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createColumn("first_name", $this->string(255));
        $this->createColumn("last_name", $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200816_090600_user_fix cannot be reverted.\n";

        return false;
    }
}
