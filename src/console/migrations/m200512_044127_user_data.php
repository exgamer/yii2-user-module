<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200512_044127_user_data
 */
class m200512_044127_user_data extends Migration
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
        $this->createColumn("social", $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200512_044127_user_data cannot be reverted.\n";

        return false;
    }
}
