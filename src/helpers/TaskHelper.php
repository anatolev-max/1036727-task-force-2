<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Html;
use app\models\Task;
use anatolev\helpers\FormatHelper;

class TaskHelper extends Helper
{
    /**
     * @param Task $task
     * @return string
     */
    public static function getCategory(Task $task): string
    {
        return Html::encode($task->category->name);
    }

    /**
     * @param Task $task
     * @return string
     */
    public static function getCity(Task $task): string
    {
        return Html::encode($task->city->name ?? '');
    }

    /**
     * @param Task $task
     * @return string
     */
    public static function getExpire(Task $task): string
    {
        return isset($task->expire) ? date('j F', strtotime($task->expire)) : '';
    }

    /**
     * @param ?string $filter
     * @return string
     */
    public static function getFilterDesc($filter): string
    {
        return match ($filter) {
            'new' => 'Новые',
            'progress' => 'В процессе',
            'overdue' => 'Просрочено',
            'closed' => 'Закрытые',
            default => 'Без фильтров'
        };
    }

    /**
     * @param Task $task
     * @return string
     */
    public static function getPublicationDate(Task $task): string
    {
        return FormatHelper::getRelativeTime($task->dt_add) . ' назад';
    }

    /**
     * @return string
     */
    public static function getRandomCategoryModifier(): string
    {
        $modifiers = ['courier', 'cargo', 'neo', 'flat'];

        return $modifiers[rand(0, count($modifiers) - 1)];
    }

    /**
     * @param Task $task
     * @return Reply[]
     */
    public static function getReplies(Task $task): array
    {
        if (!empty($task->replies) && self::isTaskCustomer($task)) {
            return $task->replies;
        }

        $callback = fn($reply) => $reply->user_id === Yii::$app->user->id;

        return array_filter($task->replies, $callback);
    }

    /**
     * @param Task $task
     * @param int $repliesCount
     * @return string
     */
    public static function getRepliesDesc(Task $task, int $repliesCount): string
    {
        return self::isTaskCustomer($task)
            ? "Отклики на задание ({$repliesCount})"
            : 'Мой отклик';
    }

    /**
     * @param Task $task
     * @return string
     */
    public static function getStatus(Task $task): string
    {
        return Html::encode($task->status->name);
    }

    /**
     * @param Task[]
     * @return Task[]
     */
    public static function getTasksWithReviews(array $tasks): array
    {
        return array_filter($tasks, fn($task) => $task?->review);
    }

    /**
     * @param Task $task
     * @return bool
     */
    public static function isExpired(Task $task): bool
    {
        return strtotime($task->expire ?? date('Y-m-d')) < strtotime('today');
    }

    /**
     * @param Task $task
     * @return bool
     */
    private static function isTaskCustomer(Task $task): bool
    {
        return $task->customer_id === Yii::$app->user->id;
    }
}
