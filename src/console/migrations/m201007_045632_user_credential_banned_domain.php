<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m201007_045632_user_credential_banned_domain
 */
class m201007_045632_user_credential_banned_domain extends Migration
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
        $this->createColumn("banned_domains", $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201007_045632_user_credential_banned_domain cannot be reverted.\n";

        return false;
    }
}
