<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\City;
use app\models\User;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $location;
    public $password;
    public $password_repeat;
    public $is_executor;
    public $city_id;

    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'password_repeat'], 'trim'],
            [['name', 'email', 'city_id', 'password', 'password_repeat'], 'required'],
            [['email'], 'string', 'max' => 128],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class],
            [['location'], 'string'],
            [['name'], 'string', 'length' => [2, 128]],
            [['password'], 'string', 'length' => [6, 255]],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password'],
            [['is_executor'], 'boolean'],
            [['city_id'], 'integer'],
            [['city_id'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_executor' => 'я собираюсь откликаться на заказы'
        ];
    }
}
