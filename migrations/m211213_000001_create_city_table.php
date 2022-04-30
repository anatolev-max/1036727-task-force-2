<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city}}`.
 */
class m211213_000001_create_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(128)->notNull(),
            'latitude' => $this->float()->notNull(),
            'longitude' => $this->float()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%city}}');
    }
}
