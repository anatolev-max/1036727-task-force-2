<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Category;
use app\models\City;

class AddTaskForm extends Model
{
    public $name;
    public $description;
    public $category_id;
    public $location;
    public $budget;
    public $expire;
    public $files;
    public $latitude;
    public $longitude;
    public $city_name;

    public function rules()
    {
        return [
            [['name', 'description'], 'trim'],
            [['name', 'description', 'category_id'], 'required'],
            [['name'], 'string', 'length' => [10, 128]],
            [['description'], 'string', 'length' => [30, 1000]],
            [['category_id'], 'integer'],
            [['category_id'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            [['location', 'city_name'], 'string'],
            [['budget'], 'integer', 'min' => 1],
            [['expire'], 'date', 'format' => 'php:Y-m-d', 'min' => strtotime('today'),
                'tooSmall' => 'Дата не может быть раньше текущего дня.'],
            [['files'], 'file', 'maxFiles' => 10],
            [['latitude', 'longitude'], 'double'],
            [['city_name'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'name',
                'message' => 'Название города не найдено в таблице городов'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'expire' => 'Срок исполнения',
            'files' => 'Добавить новый файл',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
        ];
    }
}
