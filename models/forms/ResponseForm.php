<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Task;
use app\models\Reply;
use app\models\User;

class ResponseForm extends Model
{
    public $payment;
    public $comment;
    public $task_id;
    public $user_id;

    public function rules()
    {
        return [
            [['payment', 'comment'], 'trim'],
            [['task_id', 'user_id'], 'required'],
            [['payment', 'task_id', 'user_id'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['task_id'], 'unique', 'targetClass' => Reply::class, 'targetAttribute' => ['task_id', 'user_id']],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'payment' => 'Ваша цена',
            'comment' => 'Комментарий',
            'task_id' => 'ID задания',
            'user_id' => 'ID пользователя',
        ];
    }
}
