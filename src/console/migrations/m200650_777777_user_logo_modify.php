<?php

use concepture\yii2logic\console\migrations\Migration;

/**
 * Модификация колонок с аватарками
 *
 * @author kamaelkz <kamaelkz@yandex.kz>
 */
class m200650_777777_user_logo_modify extends Migration
{
    /**
     * @inheritDoc
     */
    public function getTableName()
    {
        return 'user_property';
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $sql = "ALTER TABLE {$this->getTableName()} MODIFY logo text";
        $this->execute($sql);
    }
}
