<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m200617_061423_user_domain_assignment
 */
class m200617_061423_user_domain_assignment extends Migration
{
    public function getTableName()
    {
        return 'user_domain_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addTable([
            'user_id' =>  $this->bigInteger()->notNull(),
            'domain_id' => $this->bigInteger()->notNull(),
            'access' => $this->string(5)->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
        ]);
        $this->addPK(
            ['user_id', 'domain_id'], true);
        $this->addIndex(['user_id']);
        $this->addIndex(['domain_id']);
        $this->addForeign('user_id', 'user','id');
        $this->addForeign('domain_id', 'domain','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200617_061423_user_domain_assignment cannot be reverted.\n";

        return false;
    }
}
