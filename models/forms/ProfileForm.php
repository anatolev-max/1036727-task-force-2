<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Category;
use app\models\User;
use app\models\UserProfile;

class ProfileForm extends Model
{
    public $avatar;
    public $name;
    public $email;
    public $birthday;
    public $contact_phone;
    public $contact_tg;
    public $about;
    public $categories;
    private $id;

    public function __construct(User $user)
    {
        $this->id = $user->id;
        $this->attributes = array_merge(
            $user->attributes,
            $user->profile->attributes,
            ['categories' => array_column($user->categories, 'id')],
        );
    }

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['avatar'], 'image', 'extensions' => 'png, jpg'],
            [['name'], 'string', 'length' => [2, 128]],
            [['email', 'about'], 'string', 'max' => 128],
            [['email'], 'email'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d', 'max' => strtotime('today + 1 day'),
                'tooBig' => 'Дата не может быть позже текущего дня'],
            [['about', 'birthday', 'contact_phone', 'contact_tg'], 'default', 'value' => null],
            [['contact_phone'], 'string', 'length' => [11, 11]],
            [['contact_phone'], 'unique', 'targetClass' => UserProfile::class, 'filter' => ['!=', 'user_id', $this->id]],
            [['contact_tg'], 'string', 'max' => 64],
            [['contact_tg'], 'unique', 'targetClass' => UserProfile::class, 'filter' => ['!=', 'user_id', $this->id]],
            [['categories'], 'default', 'value' => []],
            [['categories'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id', 'allowArray' => true]
        ];
    }

    public function attributeLabels()
    {
        return [
            'avatar' => 'Сменить аватар',
            'name' => 'Ваше имя',
            'email' => 'Email',
            'birthday' => 'День рождения',
            'contact_phone' => 'Номер телефона',
            'contact_tg' => 'Telegram',
            'about' => 'Информация о себе',
            'categories' => 'Выбор специализаций'
        ];
    }
}
