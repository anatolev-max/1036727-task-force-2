<?php

/** @var yii\web\View $this */
/** @var app\models\Task[] $tasks */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\TaskHelper;

$this->title = 'Главная';

?>
<div class="landing-container">

    <div class="landing-top">
        <h1>Работа для всех.<br>Найди исполнителя на любую задачу.</h1>
        <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
            У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
            Быстро, безопасно и с гарантией. Просто, как раз, два, три.
        </p>
        <a class="button" href="<?= Url::to(['user/signup']) ?>">Создать аккаунт</a>
    </div>

    <div class="landing-center">

        <?php
        $steps = [
            [
                'class_modifier' => 'request',
                'title' => 'Публикация заявки',
                'descriptions' => ['Создайте новую заявку.', 'Опишите в ней все детали и стоимость работы.']
            ],
            [
                'class_modifier' => 'choice',
                'title' => 'Выбор исполнителя',
                'descriptions' => ['Получайте отклики от мастеров.', 'Выберите подходящего<br>вам исполнителя.']
            ],
            [
                'class_modifier' => 'discussion',
                'title' => 'Обсуждение деталей',
                'descriptions' => ['Обсудите все детали работы<br>в нашем внутреннем чате.']
            ],
            [
                'class_modifier' => 'payment',
                'title' => 'Оплата&nbsp;работы',
                'descriptions' => ['По завершении работы оплатите услугу и закройте задание']
            ]
        ];
        ?>

        <div class="landing-instruction">

            <?php foreach ($steps as $step): ?>
                <div class="landing-instruction-step">
                    <div class="instruction-circle circle-<?= $step['class_modifier'] ?>"></div>
                    <div class="instruction-description">
                        <h3><?= $step['title'] ?></h3>

                        <?php foreach ($step['descriptions'] as $desc): ?>
                            <p><?= $desc ?></p>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <?php
        $notifications = [
            [
                'class_modifier' => 'executor',
                'title' => 'Исполнителям',
                'features' => [
                    'Большой выбор заданий',
                    'Работайте где удобно',
                    'Свободный график',
                    'Удалённая работа',
                    'Гарантия оплаты'
                ]
            ],
            [
                'class_modifier' => 'customer',
                'title' => 'Заказчикам',
                'features' => [
                    'Исполнители на любую задачу',
                    'Достоверные отзывы',
                    'Оплата по факту работы',
                    'Экономия времени и денег',
                    'Выгодные цены'
                ]
            ]
        ];
        ?>

        <div class="landing-notice">

            <?php foreach ($notifications as $notice): ?>
                <div class="landing-notice-card card-<?= $notice['class_modifier'] ?>">
                    <h3><?= $notice['title'] ?></h3>
                    <ul class="notice-card-list">

                        <?php foreach ($notice['features'] as $item): ?>
                            <li><?= $item ?></li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            <?php endforeach; ?>

        </div>

    </div>

    <?php if (!empty($tasks)): ?>
        <div class="landing-bottom">
            <div class="landing-bottom-container">
                <h2>Последние задания на сайте</h2>

                <?php foreach ($tasks as $task): ?>
                    <div class="landing-task">
                        <div class="landing-task-top task-<?= TaskHelper::getRandomCategoryModifier() ?>"></div>
                        <div class="landing-task-description">
                            <h3><a href="#" class="link-regular"><?= Html::encode($task->name) ?></a></h3>
                            <p><?= Html::encode($task->description) ?></p>
                        </div>
                        <div class="landing-task-info">
                            <div class="task-info-left">
                                <p><a href="#" class="link-regular"><?= TaskHelper::category($task) ?></a></p>
                                <p><?= FormatHelper::getRelativeTime($task->dt_add) ?> назад</p>
                            </div>

                            <?php if (isset($task->budget)): ?>
                                <span><?= Html::encode($task->budget) ?> <b>&#8381;</b></span>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="landing-bottom-container">
                <a href="<?= Url::to(['user/signup']) ?>" class="button red-button">Смотреть все задания</a>
            </div>
        </div>
    <?php endif; ?>

</div>
