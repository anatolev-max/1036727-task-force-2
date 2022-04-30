<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use anatolev\service\Task;

class ActDone implements TaskActionInterface
{
    public const NAME = 'Выполнено';
    public const INNER_NAME = 'act_complete';

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
        $service = new TaskService();
        $statusId = $service->getStatusId($taskId);
        $isTaskCustomer = $service->isTaskCustomer($taskId, $userId);

        return $statusId === Task::STATUS_WORK_ID && $isTaskCustomer;
    }
}
