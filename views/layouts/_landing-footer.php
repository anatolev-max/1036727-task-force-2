<?php

/** @var yii\web\View $this */

use yii\widgets\Menu;

?>
<footer class="page-footer">
    <div class="main-container page-footer__container">

        <div class="page-footer__info">
            <p class="page-footer__info-copyright">
                © 2021, ООО «ТаскФорс»
                Все права защищены
            </p>
            <p class="page-footer__info-use">
                «TaskForce» — это сервис для поиска исполнителей на разовые задачи.
                mail@taskforce.com
            </p>
        </div>

        <div class="page-footer__links">

            <?= Menu::widget([
                'items' => [
                    ['label' => 'Задания', 'url' => ['#']],
                    ['label' => 'Мой профиль', 'url' => ['#']],
                    ['label' => 'Исполнители', 'url' => ['#']],
                    ['label' => 'Регистрация', 'url' => ['#']],
                    ['label' => 'Создать задание', 'url' => ['#']],
                    ['label' => 'Справка', 'url' => ['#']],
                ],
                'itemOptions' => ['class' => 'links__item'],
                'options' => ['class' => 'links__list']
            ]) ?>

        </div>

        <div class="page-footer__copyright">
            <a href="https://htmlacademy.ru">
                <img
                    class="copyright-logo"
                    src="./img/academy-logo.png"
                    width="185" height="63"
                    alt="Логотип HTML Academy"
                >
            </a>
        </div>

    </div>
</footer>
