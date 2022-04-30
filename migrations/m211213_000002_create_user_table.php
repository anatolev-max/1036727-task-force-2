<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m211213_000002_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dt_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'email' => $this->string(128)->notNull()->unique(),
            'name' => $this->string(128)->notNull(),
            'password' => $this->string(255)->null(),
            'city_id' => $this->integer()->unsigned()->notNull(),
            'is_executor' => $this->boolean()->notNull()->defaultValue(1),
        ]);

        // add foreign key for table `{{%city}}`
        $this->addForeignKey(
            '{{%fk-user-city_id}}',
            '{{%user}}',
            'city_id',
            '{{%city}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
