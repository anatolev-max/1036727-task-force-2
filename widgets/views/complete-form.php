<?php

/** @var yii\web\View $this */
/** @var app\models\forms\CompleteForm $model */
/** @var int $taskId */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<section style="display: none;" class="modal form-modal completion-form" id="complete-form">
    <?= Html::tag('h2', 'Завершение задания') ?>
    
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['tasks/complete']),
        'options' => ['autocomplete' => false],
    ]); ?>

        <?= $form->field($model, 'comment', ['labelOptions' => ['class' => 'form-modal-description']])
            ->textarea(['class' => 'input textarea', 'rows' => '4', 'placeholder' => 'Place your text']) ?>
        <?= $form->field($model, 'task_id', ['template' => '{input}'])->hiddenInput(['value' => $taskId]) ?>

        <?= Html::tag('p', 'Оценка', ['class' => 'form-modal-description']) ?>
        <div class="feedback-card__top--name completion-form-star">

            <?php for ($i = 1; $i <= Yii::$app->params['maxUserRating']; $i++): ?>
                <span class="star-disabled" data-rating="<?= $i ?>"></span>
            <?php endfor; ?>

        </div>
        <p></p>

        <?= $form->field($model, 'rating', ['template' => '{input}{error}'])->hiddenInput(['value' => '0', 'id' => 'rating']) ?>

        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>

    <?php ActiveForm::end(); ?>

    <?= Html::button('Закрыть', ['class' => 'form-modal-close']) ?>

</section>
