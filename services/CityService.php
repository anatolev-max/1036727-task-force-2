<?php

namespace app\services;

use app\models\City;

class CityService
{
    public function findByName(string $name): ?City
    {
        return City::findOne(['name' => $name]);
    }

    /**
     * @param float $lat
     * @param float $long
     * @return ?City
     */
    public function findByCoords(mixed $lat, mixed $long): ?City
    {
        $condition1 = ['like', 'latitude', bcdiv(floatval($lat), 1, 1)];
        $condition2 = ['like', 'longitude', bcdiv(floatval($long), 1, 1)];

        return City::find()->where($condition1)->andWhere($condition2)->one();
    }

    /**
     * @param string $query
     * @return City[]
     */
    public function findByQuery(string $query): array
    {
        return City::find()->where(['LIKE', 'name', $query])->all();
    }
}
