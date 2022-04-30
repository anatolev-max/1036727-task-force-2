<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 *
 * @property Task[] $tasks
 * @property User[] $users
 */
class City extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{city}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 128],
            
            [['latitude'], 'required'],
            [['latitude'], 'double'],
            
            [['longitude'], 'required'],
            [['longitude'], 'double'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
        ];
    }

     /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['city_id' => 'id']);
    }
}
