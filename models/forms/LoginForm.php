<?php

namespace app\models\forms;

use yii\base\Model;
use app\services\UserService;

class LoginForm extends Model
{
    public $email;
    public $password;

    public function rules(): array
    {
        return [
            [['email', 'password'], 'trim'],
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            [['password'], 'validatePassword'],
        ];
    }

    public function attributesLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль'
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = (new UserService())->getUserIdentity($this->email);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }
}
