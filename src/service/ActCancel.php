<?php

namespace anatolev\service;

use Yii;
use app\services\TaskService;
use anatolev\service\Task;

class ActCancel implements TaskActionInterface
{
    public const NAME = 'Отменить';
    public const INNER_NAME = 'act_cancel';

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

        return $statusId === Task::STATUS_NEW_ID && $isTaskCustomer;
    }
}
