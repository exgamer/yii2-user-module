<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200623_042206_credential_validation_length
 */
class m200623_042206_credential_validation_length extends Migration
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
        $this->alterColumn($this->getTableName(), 'validation', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200623_042206_credential_validation_length cannot be reverted.\n";

        return false;
    }
}
