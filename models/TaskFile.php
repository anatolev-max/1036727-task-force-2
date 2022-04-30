<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_file".
 *
 * @property int $id
 * @property string $path
 * @property int $task_id
 *
 * @property Task $task
 */
class TaskFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{task_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max' => 128],
            [['path'], 'unique'],

            [['task_id'], 'required'],
            [['task_id'], 'integer'],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'task_id' => 'Task ID',
        ];
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
