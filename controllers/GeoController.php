<?php

namespace app\controllers;

use Yii;
use yii\helpers\BaseInflector;
use yii\web\Controller;
use yii\web\Response;
use app\services\CityService;
use anatolev\helpers\FormatHelper;

class GeoController extends Controller
{
    public function actionCoords(string $geocode)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Yii::$app->geocoder->getCoords($geocode);
    }

    public function actionCities(string $query)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $key = BaseInflector::transliterate($query);

        if (!Yii::$app->cache->exists($key)) {
            $cities = (new CityService())->findByQuery($query);
            Yii::$app->cache->set($key, $cities, FormatHelper::SECONDS_PER_DAY);

            return $cities;
        }

        return Yii::$app->cache->get($key);
    }
}
