<?php

/** @var yii\web\View $this */
/** @var int $taskId */

use yii\helpers\Url;

?>
<section style="display: none;" class="modal form-modal refusal-form" id="cancel-form">
    <h2>Отмена задания</h2>
    <a
        style="float: right;"
        href="<?= Url::to(['tasks/cancel', 'taskId' => $taskId]) ?>"
        class="button__form-modal refusal-button button"
    >Отменить</a>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
