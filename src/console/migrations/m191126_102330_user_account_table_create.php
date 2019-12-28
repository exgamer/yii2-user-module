<?php
use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191108_102330_user_account_table_create
 */
class m191126_102330_user_account_table_create extends Migration
{
    public function getTableName()
    {
        return 'user_account';
    }

    public function up()
    {
        $this->addTable([
            'id' => $this->bigPrimaryKey(),
            'user_id' =>  $this->bigInteger()->notNull(),
            'balance' => $this->double()->defaultValue(0),
            'currency' => $this->bigInteger()->notNull(),
            'status' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addUniqueIndex(
            ['user_id', 'currency']);
        $this->addIndex(['status']);
        $this->addIndex(['user_id']);
        $this->addIndex(['currency']);
        $this->addForeign('user_id', 'user','id');
        $this->addForeign('currency', 'currency','id');
    }
}
