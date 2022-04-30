<?php

namespace anatolev\helpers;

use Yii;
use yii\helpers\Html;
use app\models\TaskFile;

class FileHelper
{
    public const BYTE_PER_KILOBYTE = 1024;

    /**
     * @param array $files
     * @return TaskFile[]
     */
    public static function getExists(array $files): array
    {
        $callback = function ($file) {
            if ($file instanceof TaskFile && file_exists(Yii::getAlias('@files') . '/' . $file->path)) {
                return true;
            }

            return false;
        };

        return array_filter($files, $callback);
    }

    /**
     * @param TaskFile $file
     * @return string
     */
    public static function getName(TaskFile $file): string
    {
        return Html::encode((explode('_', $file->path)[0] ?? '') . self::getExtension($file));
    }

    /**
     * @param TaskFile $file
     * @return int
     */
    public static function getSize(TaskFile $file): int
    {
        $value = filesize(Yii::getAlias('@files') . '/' . $file->path) / self::BYTE_PER_KILOBYTE;

        return ceil($value);
    }

    /**
     * @param TaskFile $file
     * @return string
     */
    private static function getExtension(TaskFile $file): string
    {
        $filePathParts = explode('.', $file->path);

        return '.' . array_pop($filePathParts);
    }
}
