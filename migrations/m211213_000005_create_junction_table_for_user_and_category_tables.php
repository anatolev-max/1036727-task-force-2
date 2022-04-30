<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%category}}`
 */
class m211213_000005_create_junction_table_for_user_and_category_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_category}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_category-user_id}}',
            '{{%user_category}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-user_category-category_id}}',
            '{{%user_category}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_category}}');
    }
}
