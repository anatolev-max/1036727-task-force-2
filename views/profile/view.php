<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\ReviewHelper;
use anatolev\helpers\TaskHelper;
use anatolev\helpers\UserHelper;

$this->title = Html::encode($user->name);

?>
<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->name) ?></h3>
    <div class="user-card">
        <div class="photo-rate">

            <img
                class="card-photo"
                src="<?= UserHelper::avatar($user) ?>"
                width="191"
                height="190"
                alt="Фото пользователя"
            >

            <div class="card-rate">
                <div class="stars-rating big">

                    <?= $this->render('//_partials/stars-rating', ['rating' => UserHelper::rating($user)]) ?>

                </div>
                <span class="current-rate"><?= UserHelper::rating($user) ?></span>
            </div>
        </div>
        <p class="user-description"><?= UserHelper::about($user) ?></p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">

                <?php foreach ($user->categories as $category): ?>
                    <li class="special-item">
                        <a
                            href="<?= Url::to(['tasks/index', 'category' => $category->inner_name]) ?>"
                            class="link link--regular"
                        ><?= Html::encode($category->name) ?></a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info">
                <span class="town-info"><?= UserHelper::city($user) ?></span>

                <?php if (isset($user->profile->birthday)): ?>
                    <?php $ageInfo = explode(' ', FormatHelper::getRelativeTime($user->profile->birthday)); ?>
                    <span class="age-info"><?= $ageInfo[0] ?></span> <?= $ageInfo[1] ?>
                <?php endif; ?>

            </p>
        </div>
    </div>

    <?php if ($tasks = TaskHelper::getTasksWithReviews($user->tasks0)): ?>
        <h4 class="head-regular">Отзывы заказчиков (<?= count($tasks) ?>)</h4>

        <?php foreach ($tasks as $task): ?>

            <?= $this->render('_review', ['task' => $task]) ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd>
                <?= UserHelper::doneTaskCount($user) ?>,
                <?= UserHelper::failedTaskCount($user) ?>
            </dd>

            <dt>Место в рейтинге</dt>
            <dd><?= Html::encode($user->placeInRating) ?> место</dd>

            <dt>Дата регистрации</dt>
            <dd><?= UserHelper::registerDate($user) ?></dd>

            <dt>Статус</dt>
            <dd><?= UserHelper::busyStatus($user) ?></dd>
        </dl>
    </div>

    <?php if ($user->showContacts): ?>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">

                <?php
                $contacts = [
                    [
                        'property' => 'contact_phone',
                        'protocol' => 'tel:',
                        'class_modifier' => 'phone'
                    ],
                    [
                        'property' => 'email',
                        'protocol' => 'mailto:',
                        'class_modifier' => 'email'
                    ],
                    [
                        'property' => 'contact_tg',
                        'protocol' => 'https://t.me/',
                        'class_modifier' => 'tg'
                    ]
                ];
                ?>

                <?php foreach ($contacts as $contact): ?>

                    <?php if ($value = $user->profile->{$contact['property']} ?? $user->{$contact['property']} ?? null): ?>
                        <li class="enumeration-item">
                            <a
                                href="<?= $contact['protocol'] ?><?= Html::encode($value) ?>"
                                class="link link--block link--<?= $contact['class_modifier'] ?>"
                            ><?= Html::encode($value) ?></a>
                        </li>
                    <?php endif; ?>

                <?php endforeach; ?>

            </ul>
        </div>
    <?php endif; ?>

</div>
