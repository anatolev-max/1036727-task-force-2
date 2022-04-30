<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $dt_add
 * @property string $name
 * @property string $description
 * @property int|null $budget
 * @property string|null $expire
 * @property string|null $location
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $city_id
 * @property int $status_id
 * @property int $category_id
 * @property int|null $executor_id
 * @property int $customer_id
 *
 * @property bool $done
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property Reply[] $replies
 * @property TaskStatus $status
 * @property TaskFile[] $files
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'length' => [10, 128]],

            [['description'], 'required'],
            [['description'], 'trim'],
            [['description'], 'string', 'length' => [30, 1000]],

            [['location'], 'string'],
            [['location'], 'default', 'value' => null],

            [['status_id'], 'required'],
            [['status_id'], 'integer'],

            [['city_id'], 'integer'],
            [['city_id'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id'],

            [['category_id'], 'required'],
            [['category_id'], 'integer'],
            [['category_id'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],

            [['executor_id'], 'integer'],
            [['executor_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],

            [['customer_id'], 'required'],
            [['customer_id'], 'integer'],
            [['customer_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            
            [['budget'], 'integer', 'min' => 1],
            [['expire'], 'date', 'format' => 'php:Y-m-d', 'min' => strtotime('today'),
                'tooSmall' => 'Дата не может быть раньше текущего дня.'],
            [['latitude'], 'double'],
            [['longitude'], 'double'],
            [['status_id'], 'exist', 'targetClass' => TaskStatus::class, 'targetAttribute' => 'id'],
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
            'name' => 'Name',
            'description' => 'Description',
            'budget' => 'Budget',
            'expire' => 'Expire',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'city_id' => 'City ID',
            'status_id' => 'Status ID',
            'category_id' => 'Category ID',
            'executor_id' => 'Executor ID',
            'customer_id' => 'Customer ID',
        ];
    }

    public function getDone(): bool
    {
        return $this->status_id === \anatolev\service\Task::STATUS_DONE_ID;
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Review]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReview()
    {
        return $this->hasOne(Review::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatus::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(TaskFile::class, ['task_id' => 'id']);
    }
}
