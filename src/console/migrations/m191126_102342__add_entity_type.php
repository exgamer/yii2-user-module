<?php

use yii\db\Migration;
use concepture\yii2handbook\forms\EntityTypeForm;

/**
 * Class m191126_102342__add_entity_type
 */
class m191126_102342__add_entity_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $form = new EntityTypeForm();
        $form->table_name = "user";
        $form->caption = "Пользователь";
        Yii::$app->entityTypeService->create($form);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191108_102328__add_entity_types cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191108_102328__add_entity_types cannot be reverted.\n";

        return false;
    }
    */
}
