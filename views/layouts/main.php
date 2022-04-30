<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use app\assets\MainAsset;

MainAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | Taskforce</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <?= $this->render('_main-header') ?>

    <main class="main-content container<?= $this->params['mainClass'] ?? '' ?>">
        <?= $content ?>
    </main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
