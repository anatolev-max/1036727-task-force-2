<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use anatolev\service\Task;

class ActRefuse implements TaskActionInterface
{
    public const NAME = 'Отказаться';
    public const INNER_NAME = 'act_refuse';

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
        $isTaskExecutor = $service->isTaskExecutor($taskId, $userId);

        return $statusId === Task::STATUS_WORK_ID && $isTaskExecutor;
    }
}
