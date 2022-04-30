<?php

/** @var yii\web\View $this */
/** @var app\models\forms\ProfileForm $profileForm */
/** @var app\models\forms\SecurityForm $securityForm */
/** @var app\models\Category[] $categories */

use yii\widgets\Menu;

$this->title = 'Настройки';
$this->params['mainClass'] = ' main-content--left';

?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>

    <?php
    $menuItems = [
        ['label' => 'Мой профиль', 'url' => ['profile/settings', 'tab' => 'profile']],
        ['label' => 'Безопасность', 'url' => ['profile/settings', 'tab' => 'security']],
    ];
    ?>

    <?= Menu::widget([
        'items' => $menuItems,
        'activeCssClass' => 'side-menu-item--active',
        'itemOptions' => ['class' => 'side-menu-item'],
        'labelTemplate' => '<a class="link link--nav">{label}</a>',
        'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
        'options' => ['class' => 'side-menu-list']
    ]); ?>

</div>
<div class="my-profile-form">

    <?php if (in_array($tab, ['profile', 'security'])): ?>

        <?php $model = "{$tab}Form"; ?>
        <?= $this->render("//_partials/{$tab}-form", ['model' => $$model, 'categories' => $categories]) ?>

    <?php endif; ?>

    <!-- <form>
        <h3 class="head-main head-regular">Мой профиль</h3>
        <div class="photo-editing">
            <div>
                <p class="form-label">Аватар</p>
                <img class="avatar-preview" src="img/man-glasses.png" width="83" height="83">
            </div>
            <input hidden value="Сменить аватар" type="file" id="button-input">
            <label for="button-input" class="button button--black"> Сменить аватар</label>
        </div>
        <div class="form-group">
            <label class="control-label" for="profile-name">Ваше имя</label>
            <input id="profile-name" type="text">
            <span class="help-block">Error description is here</span>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <label class="control-label" for="profile-email">Email</label>
                <input id="profile-email" type="email">
                <span class="help-block">Error description is here</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="profile-date">День рождения</label>
                <input id="profile-date" type="date">
                <span class="help-block">Error description is here</span>
            </div>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <label class="control-label" for="profile-phone">Номер телефона</label>
                <input id="profile-phone" type="tel">
                <span class="help-block">Error description is here</span>
            </div>
            <div class="form-group">
                <label class="control-label" for="profile-tg">Telegram</label>
                <input id="profile-tg" type="text">
                <span class="help-block">Error description is here</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="profile-info">Информация о себе</label>
            <textarea id="profile-info"></textarea>
            <span class="help-block">Error description is here</span>
        </div>
        <div class="form-group">
            <p class="form-label">Выбор специализаций</p>
            <div class="checkbox-profile">
                <label class="control-label" for="сourier-services">
                    <input type="checkbox" id="сourier-services" checked>
                    Курьерские услуги</label>
                <label class="control-label" for="cargo-transportation">
                    <input id="cargo-transportation" type="checkbox">
                    Грузоперевозки</label>
                <label class="control-label" for="cleaning">
                    <input id="cleaning" type="checkbox">
                    Клининг</label>
                <label class="control-label" for="computer-help">
                    <input id="computer-help" type="checkbox" checked>
                    Компьютерная помощь</label>
            </div>
        </div>
        <input type="submit" class="button button--blue" value="Сохранить">
    </form> -->

</div>
