<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m201016_054626_user_add_interface_iso_column
 */
class m201016_054626_user_add_interface_iso_column extends Migration
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
        $this->createColumn('interface_iso', $this->string(6));
    }
}
