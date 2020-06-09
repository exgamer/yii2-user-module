<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200609_105330_user_credential_add
 */
class m200609_105330_user_credential_add extends Migration
{
    /**
     * @inheritDoc
     */
    public function getTableName()
    {
        return 'user_credential';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createColumn("generated", $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200609_105330_user_credential_add cannot be reverted.\n";

        return false;
    }
}
