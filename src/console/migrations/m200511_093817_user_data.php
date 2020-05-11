<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200511_093817_user_data
 */
class m200511_093817_user_data extends Migration
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
        $this->createColumn("famous", $this->smallInteger()->defaultValue(0));
        $this->createColumn("website", $this->string(255));
        $this->createColumn("last_login", $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200511_093817_user_data cannot be reverted.\n";

        return false;
    }
}
