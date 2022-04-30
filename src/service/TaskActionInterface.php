<?php

namespace anatolev\service;

interface TaskActionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getInnerName(): string;

    /**
     * @param int $taskId
     * @param int $userId
     * @return bool
     */
    public function checkUserRights(int $taskId, int $userId): bool;
}
