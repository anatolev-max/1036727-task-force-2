<?php

/** @var yii\web\View $this */
/** @var array $period_values */
/** @var app\models\Category[] $categories */
/** @var app\models\forms\SearchForm $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Новые задания';

?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

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
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">

            <?php $form = ActiveForm::begin([
                'options' => ['autocomplete' => 'off'],
            ]) ?>

                <?= Html::tag('h4', 'Категории', ['class' => 'head-card']) ?>
                <?= $form->field($model, 'categories[]', ['template' => '{input}'])->checkboxList(
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

                <?= Html::tag('h4', 'Дополнительно', ['class' => 'head-card']) ?>
                <?= $form
                    ->field($model, 'isTelework', ['template' => "{input}\n{label}"])
                    ->checkbox(enclosedByLabel: false) ?>

                <?= $form
                    ->field($model, 'no_response', ['template' => "{input}\n{label}"])
                    ->checkbox(enclosedByLabel: false) ?>

                <?= Html::tag('h4', 'Период', ['class' => 'head-card']) ?>
                <?= $form->field($model, 'period_value', ['template' => '{input}'])->dropDownList($period_values) ?>

                <?= Html::submitButton('Искать', ['class' => 'button button--blue']) ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>
