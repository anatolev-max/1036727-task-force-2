<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use anatolev\helpers\UserHelper;

$user = $this->context->user;

?>
<header class="page-header">
    <nav class="main-nav">
        <a href="<?= Yii::$app->homeUrl ?>" class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width="227" height="60" alt="taskforce">
        </a>

        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="nav-wrapper">

                <?php
                $menuItems = [
                    ['label' => 'Мои задания', 'url' => ['tasks/user-tasks']],
                    ['label' => 'Новые задания','url' => ['tasks/index']],
                    ['label' => 'Настройки', 'url' => ['profile/settings']]
                ];

                $menuItem = ['label' => 'Создать задание', 'url' => ['tasks/create']];
                Yii::$app->user->can('customer') && array_splice($menuItems, 2, 0, array($menuItem));
                ?>

                <?= Menu::widget([
                    'items' => $menuItems,
                    'activeCssClass' => 'list-item--active',
                    'itemOptions' => ['class' => 'list-item'],
                    'labelTemplate' => '<a class="link link--nav">{label}</a>',
                    'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                    'options' => ['class' => 'nav-list']
                ]); ?>

            </div>
        <?php endif; ?>

    </nav>

    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="user-block">
            <a href="<?= Yii::$app->user->can('executor') ? Url::to(['profile/view', 'userId' => $user->id]) : '#' ?>">
                <img
                    class="user-photo"
                    src="<?= $this->params['avatar'] ?? UserHelper::getAvatar($user) ?>"
                    width="55"
                    height="55"
                    alt="Аватар"
                >
            </a>
            <div class="user-menu">
                <p class="user-name"><?= Html::encode($user->name) ?></p>
                <div class="popup-head">

                    <?= Menu::widget([
                        'items' => [
                            ['label' => 'Настройки', 'url' => ['profile/settings']],
                            ['label' => 'Связаться с нами', 'url' => ['#']],
                            ['label' => 'Выход из системы', 'url' => ['user/logout']]
                        ],
                        'itemOptions' => ['class' => 'menu-item'],
                        'linkTemplate' => '<a href="{url}" class="link">{label}</a>',
                        'options' => ['class' => 'popup-menu']
                    ]); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

</header>
