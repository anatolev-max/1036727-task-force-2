<?php

/** @var yii\web\View $this */
/** @var app\models\Task $task */
/** @var app\models\forms\CompleteForm $completeForm */
/** @var app\models\forms\ResponseForm $responseForm */
/** @var anatolev\service\TaskAction $availableAction */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\ModalFormAsset;
use app\widgets\ModalForm;
use anatolev\helpers\FileHelper;
use anatolev\helpers\TaskHelper;

ModalFormAsset::register($this);

$this->title = Html::encode($task->name);

?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name) ?></h3>

        <?php if (isset($task->budget)): ?>
            <p class="price price--big"><?= Html::encode($task->budget) ?> &#8381;</p>
        <?php endif; ?>

    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>

    <?php if (isset($availableAction)): ?>
        <a
            href="#"
            class="button button--blue open-modal"
            data-for="<?= explode('_', $availableAction->getInnerName())[1] ?>-form"
        ><?= Html::encode($availableAction->getName()) ?></a>
    <?php endif; ?>

    <?php if (isset($task->location, $task->latitude, $task->longitude, $task->city)): ?>
        <div class="task-map">

            <div
                id="map"
                style="width: 725px; height: 346px"
                data-lat="<?= Html::encode($task->latitude) ?>"
                data-long="<?= Html::encode($task->longitude) ?>"
            ></div>

            <p class="map-address town"><?= TaskHelper::city($task) ?></p>
            <p class="map-address"><?= Html::encode($task->location) ?></p>

        </div>
    <?php endif; ?>

    <?php if ($replies = TaskHelper::getReplies($task)): ?>
        <h4 class="head-regular"><?= TaskHelper::getRepliesDesc($task, count($replies)) ?></h4>

        <?php foreach ($replies as $reply): ?>

            <?= $this->render('_reply', ['reply' => $reply, 'isExpiredTask' => TaskHelper::isExpired($task)]) ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Статус</dt>
            <dd><?= TaskHelper::status($task) ?></dd>

            <dt>Категория</dt>
            <dd><?= TaskHelper::category($task) ?></dd>

            <dt>Дата публикации</dt>
            <dd><?= TaskHelper::publicationDate($task) ?></dd>

            <?php if (isset($task->expire)): ?>
                <dt>Срок выполнения</dt>
                <dd><?= TaskHelper::expire($task) ?></dd>
            <?php endif; ?>

        </dl>
    </div>

    <?php if ($files = FileHelper::getExists($task->files)): ?>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">

                <?php foreach ($files as $file): ?>

                    <li class="enumeration-item">
                        <a
                            href="<?= Url::to([Yii::getAlias('@files') . '/' . $file->path]) ?>"
                            class="link link--block link--clip"
                            download
                        ><?= FileHelper::getName($file) ?></a>
                        <p class="file-size"><?= FileHelper::getSize($file) ?> Кб</p>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>
    <?php endif; ?>

</div>

<?= ModalForm::widget(['formType' => 'cancel', 'taskId' => $task->id]) ?>
<?= ModalForm::widget(['formType' => 'refuse', 'taskId' => $task->id]) ?>
<?= ModalForm::widget(['formType' => 'complete', 'taskId' => $task->id, 'model' => $completeForm]) ?>
<?= ModalForm::widget(['formType' => 'response', 'taskId' => $task->id, 'model' => $responseForm]) ?>

<div style="display: none;" class="overlay"></div>
