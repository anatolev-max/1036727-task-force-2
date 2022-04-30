<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reply}}`.
 */
class m211213_000010_create_reply_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reply}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dt_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'payment' => $this->integer()->unsigned()->null(),
            'comment' => $this->string(255)->null(),
            'denied' => $this->boolean()->notNull()->defaultValue(0),
            'task_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-reply-task_id}}',
            '{{%reply}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-reply-user_id}}',
            '{{%reply}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->execute('ALTER TABLE reply ADD UNIQUE INDEX task_user (task_id, user_id)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reply}}');
    }
}
