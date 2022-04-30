<?php

/** @var yii\web\View $this */
/** @var app\models\forms\ResponseForm $model */
/** @var int $taskId */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<section style="display: none;" class="modal form-modal response-form" id="respond-form">
    <?= Html::tag('h2', 'Отклик на задание') ?>

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['reply/create']),
        'options' => ['autocomplete' => false],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-modal-description']
        ]
    ]); ?>

        <?= $form->field($model, 'payment')->textInput(['class' => 'response-form-payment input input-middle input-money']) ?>
        <?= $form->field($model, 'comment')->textarea(['class' => 'input textarea', 'placeholder' => 'Place your text', 'rows' => '4']) ?>
        <?= $form->field($model, 'task_id', ['template' => '{input}'])->hiddenInput(['value' => $taskId]) ?>
        <?= $form->field($model, 'user_id', ['template' => '{input}'])->hiddenInput(['value' => Yii::$app->user->id]) ?>

        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>

    <?php ActiveForm::end(); ?>

    <?= Html::button('Закрыть', ['class' => 'form-modal-close']) ?>

</section>
