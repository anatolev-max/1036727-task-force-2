<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $dt_add
 * @property string $email
 * @property string $name
 * @property string $password
 * @property int $city_id
 * @property bool $is_executor
 *
 * @property City $city
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Reply[] $replies
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property Category[] $categories
 * @property UserProfile $profile
 */
class User extends ActiveRecord
{
    public $isBusy;
    public $placeInRating;
    public $showContacts;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'trim'],
            [['email'], 'string', 'max' => 128],
            [['email'], 'email'],
            [['email'], 'unique'],

            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'length' => [2, 128]],

            [['password'], 'trim'],
            [['password'], 'string', 'max' => 255],

            [['city_id'], 'required'],
            [['city_id'], 'integer'],
            [['city_id'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],

            [['is_executor'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
            'city_id' => 'City ID',
            'is_executor' => 'Is Executor',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['recipient_id' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::class, ['sender_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this
            ->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('user_category', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserProfile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }
}
