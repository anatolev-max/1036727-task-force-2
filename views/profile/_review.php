<?php

/** @var yii\web\View $this */
/** @var app\models\Task $task */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\ReviewHelper;
use anatolev\helpers\TaskHelper;
use anatolev\helpers\UserHelper;

?>
<div class="response-card">

    <img
        class="customer-photo"
        src="<?= UserHelper::avatar($task->customer) ?>"
        width="120"
        height="127"
        alt="Фото заказчиков"
    >

    <div class="feedback-wrapper">
        <p class="feedback"><?= ReviewHelper::comment($task) ?></p>
        <p class="task">
            Задание «<a
                href="<?= Url::to(['tasks/view', 'taskId' => $task->id]) ?>"
                class="link link--small"
            ><?= Html::encode($task->name) ?></a>» выполнено
        </p>
    </div>
    <div class="feedback-wrapper">
        <div class="stars-rating small">

            <?= $this->render('//_partials/stars-rating', ['rating' => $task->review->rating]) ?>

        </div>
        <p class="info-text">
            <span class="current-time"><?= ReviewHelper::dateAdd($task) ?> </span>назад
        </p>
    </div>
</div>
