<?php
use concepture\yii2logic\console\migrations\Migration;

/**
 * Class m191108_102340_user_account_operation
 */
class m191126_102340_user_account_operation extends Migration
{
    public function getTableName()
    {
        return 'user_account_operation';
    }

    public function up()
    {
        $this->addTable([
            'id' => $this->bigPrimaryKey(),
            'account_id' =>  $this->bigInteger()->notNull(),
            'sum' => $this->double(),
            'type' => $this->smallInteger()->notNull(),
            'currency' => $this->bigInteger()->notNull(),
            'status' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()") ),
        ]);
        $this->addIndex(['status']);
        $this->addIndex(['user_id']);
        $this->addIndex(['account_id']);
        $this->addForeign('user_id', 'user','id');
        $this->addForeign('account_id', 'user_account','id');
    }
}
