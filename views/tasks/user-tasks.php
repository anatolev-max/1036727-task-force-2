<?php

/** @var yii\web\View $this */
/** @var ?string $filter */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\widgets\ListView;
use yii\widgets\Menu;
use anatolev\helpers\TaskHelper;

$user = $this->context->user;
$this->title = 'Мои задания';

?>
<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <ul class="side-menu-list">

        <?php
        $menuItems = [
            ['label' => 'В процессе', 'url' => ['tasks/user-tasks', 'filter' => 'progress']],
            ['label' => 'Закрытые', 'url' => ['tasks/user-tasks', 'filter' => 'closed']]
        ];

        $newItem = ['label' => 'Новые', 'url' => ['tasks/user-tasks', 'filter' => 'new']];
        $overdueItem = ['label' => 'Просрочено','url' => ['tasks/user-tasks', 'filter' => 'overdue']];

        $user->is_executor
            ? array_splice($menuItems, 1, 0, array($overdueItem))
            : array_splice($menuItems, 0, 0, array($newItem));
        ?>

        <?= Menu::widget([
            'items' => $menuItems,
            'activeCssClass' => 'side-menu-item--active',
            'itemOptions' => ['class' => 'side-menu-item'],
            'labelTemplate' => '<a class="link link--nav">{label}</a>',
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
            'options' => ['class' => 'side-menu-list']
        ]); ?>

    </ul>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular"><?= TaskHelper::getFilterDesc($filter) ?></h3>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_task',
        'pager' => [
            'prevPageLabel' => '',
            'nextPageLabel' => '',
            'pageCssClass' => 'pagination-item',
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageCssClass' => 'pagination-item mark',
            'activePageCssClass' => 'pagination-item--active',
            'options' => ['class' => 'pagination-list'],
            'linkOptions' => ['class' => 'link link--page'],
            'options' => [
                'class' => 'pagination-list',
            ],
        ],
    ]) ?>

</div>
