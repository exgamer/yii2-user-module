<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200422_062136_cread_fk_fix
 */
class m200422_062136_cread_fk_fix extends Migration
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
        try {
            $this->dropForeignKey('fk_user_credential_parent_id_user_id', $this->getTableName());
        }catch (Exception $exception){

        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200422_062136_cread_fk_fix cannot be reverted.\n";

        return false;
    }
}
