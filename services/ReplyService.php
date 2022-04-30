<?php

namespace app\services;

use app\models\Reply;
use app\models\forms\ResponseForm;
use anatolev\service\Task;

class ReplyService
{
    /**
     * @param ResponseForm $model
     * @return int
     */
    public function create(ResponseForm $model): int
    {
        $reply = new Reply();
        $reply->attributes = $model->attributes;
        $reply->save();

        return $model->task_id;
    }

    /**
     * @param int $replyId
     * @return int
     */
    public function accept(int $replyId): int
    {
        $reply = Reply::findOne($replyId);
        $task = (new TaskService())->findOne($reply->task_id);

        $task->executor_id = $reply->user_id;
        $task->status_id = Task::STATUS_WORK_ID;
        $task->save();

        return $reply->task_id;
    }

    /**
     * @param $replyId
     * @return int
     */
    public function refuse(int $replyId): int
    {
        $reply = Reply::findOne($replyId);
        $reply->denied = 1;
        $reply->save();

        return $reply->task_id;
    }

    /**
     * @param int $replyId
     * @return ?Reply
     */
    public function findOne(int $replyId): ?Reply
    {
        return Reply::findOne($replyId);
    }

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function exist(int $taskId, int $userId): bool
    {
        $condition = ['task_id' => $taskId, 'user_id' => $userId];

        return Reply::find()->where($condition)->exists();
    }
}
