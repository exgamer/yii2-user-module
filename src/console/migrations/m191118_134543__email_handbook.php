<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191118_134543__email_handbook
 */
class m191118_134543__email_handbook extends Migration
{
    function getTableName()
    {
        return 'email_handbook';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addTable([
            'id' => $this->bigPrimaryKey(),
            'email' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
        ]);
        $this->addUniqueIndex(['email']);
        $this->addIndex(['email']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191118_134543__email_handbook cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191118_134543__email_handbook cannot be reverted.\n";

        return false;
    }
    */
}
