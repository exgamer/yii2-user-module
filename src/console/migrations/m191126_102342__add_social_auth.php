<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191126_102342__add_social_auth
 */
class m191126_102342__add_social_auth extends Migration
{
    public function getTableName()
    {
        return 'user_social_auth';
    }

    public function up()
    {
        $this->addTable( [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->bigInteger()->notNull(),
            'source_user_id' => $this->string()->notNull(),
            'source_name' => $this->string()->notNull(),
            'source_title' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
        ]);
        $this->addIndex(['user_id']);
        $this->addForeign('user_id', 'user','id');
    }
}
