<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Task;

class CompleteForm extends Model
{
    public $comment;
    public $rating;
    public $task_id;

    public function rules()
    {
        return [
            [['comment', 'rating', 'task_id'], 'required'],
            [['comment'], 'string', 'max' => 255],
            [['rating'], 'integer', 'min' => 1, 'max' => Yii::$app->params['maxUserRating']],
            [['task_id'], 'integer'],
            [['task_id'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'comment' => 'Комментарий',
            'rating' => 'Оценка',
        ];
    }
}
