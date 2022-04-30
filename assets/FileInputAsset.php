<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $js = [
        'js/file-input.js',
    ];
}
