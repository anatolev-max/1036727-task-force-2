<?php

/** @var yii\web\View $this */
/** @var app\models\forms\SecurityForm $model */
/** @var app\models\Category[] $categories */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'options' => ['autocomplete' => 'off'],
]); ?>

    <?= Html::tag('h3', 'Безопасность', ['class' => 'head-main head-regular']) ?>

    <?php if ($this->context->user->is_executor): ?>

        <?= $form->field($model, 'private_contacts')->checkbox(enclosedByLabel: false) ?>

    <?php endif; ?>

    <?php if ($this->context->user?->password): ?>
        <?= $form->field($model, 'old_password')->passwordInput(); ?>
    <?php endif; ?>

    <?= $form->field($model, 'new_password')->passwordInput(); ?>
    <?= $form->field($model, 'new_password_repeat')->passwordInput(); ?>

    <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>

<?php ActiveForm::end(); ?>
