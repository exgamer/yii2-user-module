<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191005_101557_user_cred_fix
 */
class m191005_101557_user_cred_fix extends Migration
{
    function getTableName()
    {
        return 'user_credential';
    }

    public function up()
    {
        $this->createColumn("domain_id", $this->bigInteger());
//        $this->addUniqueIndex(
//            ['user_id', 'identity', 'type', 'domain_id']);
    }
}
