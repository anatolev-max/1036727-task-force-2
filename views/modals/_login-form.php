<?php

/** @var yii\web\View $this */
/** @var app\models\forms\LoginForm $model */

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<section class="modal enter-form form-modal" id="enter-form">
    <?= Html::tag('h2', 'Вход на сайт') ?>

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['user/login']),
        'enableAjaxValidation' => true,
        'options' => ['autocomplete' => 'off'],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-modal-description'],
            'inputOptions' => ['class' => 'enter-form-email input input-middle']
        ]
    ]); ?>

        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

        <div style="display: flex; flex-direction: column;">
            <?= Html::submitButton('Войти', ['style' => 'align-self: stretch; margin-top: 35px;', 'class' => 'button']) ?>

            <?php $authChoice = AuthChoice::begin([
                'baseAuthUrl' => ['user/auth'],
                'popupMode' => false
            ]); ?>

                <?= $authChoice->clientLink(
                    $authChoice->getClients()['vkontakte'],
                    'Вход через вконтакте',
                    [
                        'style' => 'align-self: stretch; margin-top: 15px; text-align: center;',
                        'class' => 'button',
                    ]
                ); ?>

            <?php AuthChoice::end(); ?>

        </div>

    <?php ActiveForm::end(); ?>

    <?= Html::button('Закрыть', ['class' => 'form-modal-close']) ?>

</section>
