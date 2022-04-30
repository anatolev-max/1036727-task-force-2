<?php

namespace anatolev\helpers;

use yii\helpers\Html;
use app\models\Reply;

class ReplyHelper extends Helper
{
    /**
     * @param Reply $reply
     * @return string
     */
    public static function getAuthor(Reply $reply): string
    {
        return Html::encode($reply->user->name);
    }

    /**
     * @param Reply $reply
     * @return int
     */
    public static function getRating(Reply $reply): int
    {
        return $reply->user->profile->current_rate;
    }
}
