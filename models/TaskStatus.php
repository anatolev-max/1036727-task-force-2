<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "task_status".
 *
 * @property int $id
 * @property string $name
 * @property string $inner_name
 *
 * @property Task[] $tasks
 */
class TaskStatus extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{task_status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],

            [['inner_name'], 'required'],
            [['inner_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'inner_name' => 'Внутреннее имя',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['status_id' => 'id']);
    }
}
