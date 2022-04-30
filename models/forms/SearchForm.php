<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Category;

class SearchForm extends Model
{
    public $categories;
    public $isTelework;
    public $no_response;
    public $period_value;

    public const PERIOD_VALUES = [
        '0' => 'Без ограничений',
        '1' => '1 час',
        '12' => '12 часов',
        '24' => '24 часа'
    ];

    public function rules(): array
    {
        return [
            [['categories'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id',
                'allowArray' => true],
            [['isTelework', 'no_response'], 'boolean'],
            [['period_value'], 'in', 'range' => array_keys(self::PERIOD_VALUES)]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'isTelework' => 'Удалённая работа',
            'no_response' => 'Без откликов'
        ];
    }
}
