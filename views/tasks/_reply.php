<?php

/** @var yii\web\View $this */
/** @var bool $isExpiredTask */
/** @var app\models\Reply $reply */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\ReplyHelper;
use anatolev\helpers\TaskHelper;
use anatolev\helpers\UserHelper;
use anatolev\service\Task;

?>
<div class="response-card">

    <img
        class="customer-photo"
        src="<?= UserHelper::avatar($reply->user) ?>"
        width="146"
        height="156"
        alt="Фото заказчиков"
    >

    <div class="feedback-wrapper">
        <a
            href="<?= $reply->user->is_executor ? Url::to(['profile/view', 'userId' => $reply->user->id]) : '#' ?>"
            class="link link--block link--big"
        ><?= ReplyHelper::author($reply) ?></a>
        <div class="response-wrapper">
            <div class="stars-rating small">

                <?= $this->render('//_partials/stars-rating', ['rating' => ReplyHelper::rating($reply)]) ?>

            </div>
            <?php

            $reviews = array_filter($reply->user->tasks0, fn($task) => $task?->review);
            $review_count = count($reviews);
            $review_word = FormatHelper::getNounPluralForm($review_count, 'отзыв', 'отзыва', 'отзывов');

            ?>
            <p class="reviews"><?= "{$review_count} {$review_word}" ?></p>
        </div>

        <?php if (isset($reply->comment)): ?>
            <p class="response-message"><?= Html::encode($reply->comment) ?></p>
        <?php endif; ?>

    </div>
    <div class="feedback-wrapper">
        <p class="info-text">
            <span class="current-time"><?= FormatHelper::getRelativeTime($reply->dt_add) ?> </span>назад
        </p>

        <?php if (isset($reply->payment)): ?>
            <p class="price price--small"><?= Html::encode($reply->payment) ?> &#8381;</p>
        <?php endif; ?>

    </div>

    <?php if (
        !$isExpiredTask
        && !$reply->denied
        && $reply->task->customer_id === Yii::$app->user->id
        && $reply->task->status_id === Task::STATUS_NEW_ID
    ): ?>
        <div class="button-popup">
            <a
                href="<?= Url::to(['reply/accept', 'replyId' => $reply->id]) ?>"
                class="button button--blue button--small"
            >Принять</a>

            <a
                href="<?= Url::to(['reply/refuse', 'replyId' => $reply->id]) ?>"
                class="button button--orange button--small"
            >Отказать</a>
        </div>
    <?php endif; ?>

</div>
