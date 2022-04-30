<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $id
 * @property string|null $birthday
 * @property string|null $about
 * @property string|null $avatar_path
 * @property string|null $contact_phone
 * @property string|null $contact_tg
 * @property bool $private_contacts
 * @property float $current_rate
 * @property int $done_task_count
 * @property int $failed_task_count
 * @property int $user_id
 *
 * @property PhotoOfWork[] $photoOfWorks
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{user_profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['about'], 'trim'],
            [['about'], 'string', 'max' => 128],

            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],

            [['avatar_path'], 'string', 'max' => 128],
            [['avatar_path'], 'unique'],

            [['contact_phone'], 'string', 'length' => [11, 11]],
            [['contact_phone'], 'unique'],

            [['contact_tg'], 'string', 'max' => 64],
            [['contact_tg'], 'unique'],

            [['private_contacts'], 'boolean'],
            [['done_task_count'], 'integer'],
            [['failed_task_count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'birthday' => 'Birthday',
            'about' => 'About',
            'avatar_path' => 'Avatar Path',
            'contact_phone' => 'Contact Phone',
            'contact_tg' => 'Contact Tg',
            'private_contacts' => 'Private Contacts',
            'current_rate' => 'Current Rate',
            'done_task_count' => 'Done Task Count',
            'failed_task_count' => 'Failed Task Count',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[PhotoOfWorks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoOfWorks()
    {
        return $this->hasMany(PhotoOfWork::class, ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
