<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Category;
use app\models\User;
use app\services\UserService;

class SecurityForm extends Model
{
    public $private_contacts;
    public $old_password;
    public $new_password;
    public $new_password_repeat;

    public function __construct(User $user)
    {
        $this->attributes = $user->profile->attributes;
    }

    public function rules()
    {
        return [
            [['old_password'], 'required', 'when' => $this->getOldPasswdCallback(),
                'whenClient' => "() => $('securityform-old_password);"],
            [['old_password'], 'string', 'length' => [6, 255], 'when' => $this->getOldPasswdCallback()],
            [['old_password'], 'validatePassword', 'when' => $this->getOldPasswdCallback()],

            [['new_password'], 'required', 'when' => $this->getNewPasswdCallback()],
            [['new_password'], 'string', 'length' => [6, 255], 'when' => $this->getNewPasswdCallback()],

            [['new_password_repeat'], 'required', 'when' => $this->getNewPasswdCallback()],
            [['new_password_repeat'], 'compare', 'compareAttribute' => 'new_password',
                'when' => $this->getNewPasswdCallback()],

            [['private_contacts'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'private_contacts' => 'Показывать мои контакты только заказчику',
            'old_password' => 'Старый пароль',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Повтор нового пароля'
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $email = (new UserService())->findOne(Yii::$app->user->id)->email;
            $user = (new UserService())->getUserIdentity($email);
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Неправильный пароль');
            }
        }
    }

    private function getOldPasswdCallback(): callable
    {
        return fn($model) => (new UserService())->passwordIsset();
    }

    private function getNewPasswdCallback(): callable
    {
        return fn($model) => mb_strlen($model->old_password);
    }
}
