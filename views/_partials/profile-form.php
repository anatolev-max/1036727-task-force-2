<?php

/** @var yii\web\View $this */
/** @var app\models\Category[] $categories */
/** @var app\models\forms\ProfileForm $model */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\FileInputAsset;
use anatolev\helpers\UserHelper;

FileInputAsset::register($this);

$user = $this->context->user;
$this->params['avatar'] = $avatar = UserHelper::getAvatar($user);

?>
<?php $form = ActiveForm::begin([
    'options' => ['autocomplete' => 'off'],
]); ?>

    <?= Html::tag('h3', 'Мой профиль', ['class' => 'head-main head-regular']) ?>
    <div class="photo-editing">
        <div>
            <p class="form-label">Аватар</p>
            <img class="avatar-preview" src="<?= $avatar ?>" width="83" height="83">
        </div>
        <?= $form
            ->field($model, 'avatar', ['template' => "{input}{label}", 'labelOptions' => ['class' => 'button button--black']])
            ->fileInput(['style' => 'display: none;']) ?>
    </div>

    <?= $form->field($model, 'name')->textInput() ?>

    <div class="half-wrapper">
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->input('email') ?>
        <?= $form->field($model, 'birthday', ['enableAjaxValidation' => true])->input('date') ?>
    </div>

    <div class="half-wrapper">
        <?= $form->field($model, 'contact_phone', ['enableAjaxValidation' => true])->input('tel') ?>
        <?= $form->field($model, 'contact_tg', ['enableAjaxValidation' => true])->textInput() ?>
    </div>

    <?= $form->field($model, 'about')->textarea() ?>

    <?php if ($this->context->user->is_executor): ?>
        <?= $form->field($model, 'categories[]', ['template' => '{label}{input}'])->checkboxList(
            ArrayHelper::map($categories, 'id', 'name'),
            [
                'separator' => '<br>',
                'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                    settype($model->categories, 'array');
                    $checked = in_array($value, $model->categories) ? ' checked' : '';
                    $html = "<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"{$checked}>";

                    return "<label>{$html}{$label}</label>";
                }
            ]
        ); ?>
    <?php endif; ?>

    <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>

<?php ActiveForm::end(); ?>
