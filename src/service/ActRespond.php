<?php

namespace anatolev\service;

use Yii;
use app\services\ReplyService;
use app\services\TaskService;
use app\services\UserService;

class ActRespond implements TaskActionInterface
{
    public const NAME = 'Откликнуться';
    public const INNER_NAME = 'act_respond';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getInnerName(): string
    {
        return self::INNER_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function checkUserRights(int $taskId, int $userId): bool
    {
        $statusId = (new TaskService())->getStatusId($taskId);
        $isExecutor = Yii::$app->user->can('executor');
        $replyExist = (new ReplyService())->exist($taskId, $userId);

        return !$replyExist && $statusId === Task::STATUS_NEW_ID && $isExecutor;
    }
}
