<?php

namespace anatolev\service;

use Yii;
use anatolev\exception\SourceFileException;
use anatolev\exception\StatusNotExistException;
use app\services\TaskService;

class Task
{
    public const STATUS_NEW_ID = 1;
    public const STATUS_CANCEL_ID = 2;
    public const STATUS_WORK_ID = 3;
    public const STATUS_DONE_ID = 4;
    public const STATUS_FAILED_ID = 5;

    private array $actions = [];

    public function __construct(
        private int $taskId
    ) {
    }

    /**
     * @return array
     */
    public function getStatusMap(): array
    {
        return [
            self::STATUS_NEW_ID,
            self::STATUS_CANCEL_ID,
            self::STATUS_WORK_ID,
            self::STATUS_DONE_ID,
            self::STATUS_FAILED_ID
        ];
    }

    /**
     * @throws StatusNotExistException
     * @return ?TaskActionInterface
     */
    public function getAvailableAction(): ?TaskActionInterface
    {
        $statusId = (new TaskService())->getStatusId($this->taskId);

        if (!in_array($statusId, $this->getStatusMap())) {
            throw new StatusNotExistException("Статус не существует");
        }

        $array = [
            self::STATUS_NEW_ID => [
                $this->getAction(ActCancel::class),
                $this->getAction(ActRespond::class)
            ],
            self::STATUS_WORK_ID => [
                $this->getAction(ActDone::class),
                $this->getAction(ActRefuse::class)
            ]
        ];

        $availableAction = null;

        foreach ($array[$statusId] ?? [] as $action) {
            if ($action->checkUserRights($this->taskId, Yii::$app->user->id)) {
                $availableAction = $action;
            }
        }

        return $availableAction;
    }

    /**
     * @param string $action
     * @throws SourceFileException
     * @return TaskActionInterface
     */
    private function getAction(string $action): TaskActionInterface
    {
        if (!class_exists($action)) {
            throw new SourceFileException("Класс действия не найден");
        }

        if (!isset($this->actions[$action])) {
            $this->actions[$action] = new $action();
        }

        return $this->actions[$action];
    }
}
