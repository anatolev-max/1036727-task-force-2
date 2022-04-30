<?php

/** @var yii\web\View $this */
/** @var app\models\Category[] $categories */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\AutoCompleteAsset;
use app\assets\FileInputAsset;

AutoCompleteAsset::register($this);
FileInputAsset::register($this);

$this->title = 'Создать задание';
$this->params['mainClass'] = ' main-content--center';

?>
<div class="add-task-form regular-form">

    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
    ]); ?>

        <?= Html::tag('h3', 'Публикация нового задания', ['class' => 'head-main']) ?>

        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'description')->textarea() ?>
        <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'name')) ?>
        <?= $form->field($model, 'location')->textInput(['id' => 'autoComplete', 'style' => 'padding-left: 50px;', 'data-api-url' => Url::to(['/api/coords'])]) ?>

        <div class="half-wrapper">
            <?= $form->field($model, 'budget')->input('number') ?>
            <?= $form->field($model, 'expire', ['enableAjaxValidation' => true])->input('date', ['placeholder' => 'гггг-мм-дд']) ?>
        </div>

        <?= Html::tag('p', 'Файлы', ['class' => 'form-label']) ?>
        <div class="new-file">
            <?= $form
                ->field($model, 'files[]', ['template' => "{input}{label}", 'labelOptions' => ['class' => 'add-file']])
                ->fileInput(['style' => 'display: none;', 'multiple' => true]) ?>
        </div>

        <?= $form->field($model, 'latitude', ['template' => '{input}'])->hiddenInput(['id' => 'lat']) ?>
        <?= $form->field($model, 'longitude', ['template' => '{input}'])->hiddenInput(['id' => 'long']) ?>
        <?= $form->field($model, 'city_name', ['enableAjaxValidation' => true, 'template' => '{input}{error}'])->hiddenInput(['id' => 'city']) ?>

        <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end(); ?>

</div>
