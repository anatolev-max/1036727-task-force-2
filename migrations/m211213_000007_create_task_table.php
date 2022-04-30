<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m211213_000007_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dt_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'name' => $this->string(128)->notNull(),
            'description' => $this->text()->notNull(),
            'budget' => $this->integer()->unsigned()->null(),
            'expire' => $this->date()->null(),
            'location' => $this->string(128)->null(),
            'latitude' => $this->float()->null(),
            'longitude' => $this->float()->null(),
            'city_id' => $this->integer()->unsigned()->null(),
            'status_id' => $this->integer()->unsigned()->notNull()->defaultValue(1),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'executor_id' => $this->integer()->unsigned()->null(),
            'customer_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%city}}`
        $this->addForeignKey(
            '{{%fk-task-city_id}}',
            '{{%task}}',
            'city_id',
            '{{%city}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%task_status}}`
        $this->addForeignKey(
            '{{%fk-task-status_id}}',
            '{{%task}}',
            'status_id',
            '{{%task_status}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-task-category_id}}',
            '{{%task}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-task-executor_id}}',
            '{{%task}}',
            'executor_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-task-customer_id}}',
            '{{%task}}',
            'customer_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
    }
}
