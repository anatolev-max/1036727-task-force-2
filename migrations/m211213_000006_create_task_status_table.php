<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_status}}`.
 */
class m211213_000006_create_task_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_status}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(64)->notNull()->unique(),
            'inner_name' => $this->string(64)->notNull()->unique()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_status}}');
    }
}
