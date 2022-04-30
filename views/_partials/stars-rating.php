<?php

/** @var yii\web\View $this */
/** @var int $rating */

$maxRating = Yii::$app->params['maxUserRating'];

?>
<?php for ($i = 1; $i <= $maxRating; $i++): ?>
    <?php $className = $i <= intval($rating) ? 'fill-star' : ''; ?>

    <span class="<?= $className ?>">&nbsp;</span>

<?php endfor; ?>
