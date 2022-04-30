<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_profile}}`.
 */
class m211213_000003_create_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey()->unsigned(),
            'birthday' => $this->date()->null(),
            'about' => $this->string(128)->null(),
            'avatar_path' => $this->string(128)->null()->unique(),
            'contact_phone' => $this->string(11)->null()->unique(),
            'contact_tg' => $this->string(64)->null()->unique(),
            'private_contacts' => $this->boolean()->notNull()->defaultValue(0),
            'current_rate' => $this->float()->notNull()->defaultValue(0),
            'done_task_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'failed_task_count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'user_id' => $this->integer()->unsigned()->notNull()->unique()
        ]);

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profile}}',
            'user_id',
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
        $this->dropTable('{{%user_profile}}');
    }
}
