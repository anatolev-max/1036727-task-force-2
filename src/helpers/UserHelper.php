<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use anatolev\helpers\FormatHelper;

class UserHelper extends Helper
{
    public const DEFAULT_AVATAR_COUNT = 5;

    /**
     * @param User $user
     * @return string
     */
    public static function getAbout(User $user): string
    {
        return Html::encode($user->profile->about);
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getAvatar(User $user): string
    {
        $defaultAvatarPath = '../../img/avatars/' . rand(1, self::DEFAULT_AVATAR_COUNT) . '.png';

        if (isset($user->profile->avatar_path)) {
            $avatar_path = Yii::getAlias('@avatars') . '/' . Html::encode($user->profile->avatar_path);

            return file_exists($avatar_path) ? Url::to([$avatar_path]) : $defaultAvatarPath;
        }

        return $defaultAvatarPath;
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getBusyStatus(User $user): string
    {
        return $user->isBusy ? 'Занят' : 'Открыт для новых заказов';
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getCity(User $user): string
    {
        return Html::encode($user->city->name);
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getDoneTaskCount(User $user): string
    {
        return Html::encode($user->profile->done_task_count) . ' выполнено';
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getFailedTaskCount(User $user): string
    {
        return Html::encode($user->profile->failed_task_count) . ' провалено';
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getRating(User $user): string
    {
        return Html::encode($user->profile->current_rate);
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getRegisterDate(User $user): string
    {
        return date('j F', strtotime($user->dt_add));
    }
}
