<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Html;
use app\models\Task;
use anatolev\helpers\FormatHelper;

class ReviewHelper extends Helper
{
    /**
     * @param Task $task
     * @return string
     */
    public static function getComment(Task $task): string
    {
        return Html::encode($task->review->comment ?? '');
    }

    /**
     * @param Task $task
     * @return string
     */
    public static function getDateAdd(Task $task): string
    {
        return FormatHelper::getRelativeTime($task->review->dt_add);
    }
}
