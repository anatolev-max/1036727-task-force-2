<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_file}}`.
 */
class m211213_000008_create_task_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_file}}', [
            'id' => $this->primaryKey()->unsigned(),
            'path' => $this->string(128)->notNull()->unique(),
            'task_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-task_file-task_id}}',
            '{{%task_file}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_file}}');
    }
}
