<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "reply".
 *
 * @property int $id
 * @property string $dt_add
 * @property int|null $payment
 * @property string|null $comment
 * @property int $task_id
 * @property int $user_id
 *
 * @property Task $task
 * @property User $user
 */
class Reply extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{reply}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],

            [['comment'], 'string', 'max' => 255],
            [['comment'], 'default', 'value' => null],

            [['task_id'], 'required'],
            [['task_id'], 'integer'],
            [['task_id'], 'unique', 'targetAttribute' => ['task_id', 'user_id']],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'payment' => 'Payment',
            'comment' => 'Comment',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
