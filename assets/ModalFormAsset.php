<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class ModalFormAsset extends AssetBundle
{
    public const YANDEX_MAPS_API_URL = 'https://api-maps.yandex.ru/2.1';
    public $basePath = '@webroot';

    public $css = [
        'css/modal-form.css',
    ];

    public $js = [
        'js/modal-form.js',
    ];

    public function __construct()
    {
        $apiKey = Yii::$app->params['geocoderApiKey'];
        $this->js[] = self::YANDEX_MAPS_API_URL . "?apikey={$apiKey}&lang=ru_RU";
        $this->js[] = 'js/yandex-map.js';
    }
}
