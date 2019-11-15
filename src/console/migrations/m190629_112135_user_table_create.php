<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m190629_112135_user_table_create
 */
class m190629_112135_user_table_create extends Migration
{
    public function getTableName()
    {
        return 'user';
    }

    public function up()
    {
        $this->addTable( [
            'id' => $this->bigPrimaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'locale' => $this->integer()->notNull(),
            'domain_id' => $this->bigInteger(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
            'updated_at' => $this->dateTime()->append('ON UPDATE NOW()'),
        ]);
        $this->addIndex(['locale']);
        $this->addIndex(['domain_id']);
        $this->addForeign('locale', 'locale','id');
    }
}
