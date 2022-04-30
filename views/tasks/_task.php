<?php

/** @var yii\web\View $this */
/** @var app\models\Task $model */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\TaskHelper;

?>
<div class="task-card">
    <div class="header-task">
        <?php $href = Url::to(['tasks/view', 'taskId' => $model->id]); ?>
        <a href="<?= $href ?>" class="link link--block link--big"><?= Html::encode($model->name) ?></a>

        <?php if (isset($model->budget)): ?>
            <p class="price price--task"><?= Html::encode($model->budget) ?> &#8381;</p>
        <?php endif; ?>

    </div>
    <p class="info-text">
        <span class="current-time"><?= FormatHelper::getRelativeTime($model->dt_add) ?> </span>назад
    </p>
    <p class="task-text"><?= Html::encode($model->description) ?></p>
    <div class="footer-task">

        <?php if (isset($model->location)): ?>
            <p class="info-text town-text"><?= Html::encode($model->location) ?></p>
        <?php endif; ?>

        <p class="info-text category-text"><?= TaskHelper::category($model) ?></p>
        <a href="<?= $href ?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>
