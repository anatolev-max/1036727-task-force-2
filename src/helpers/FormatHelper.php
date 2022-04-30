<?php

namespace anatolev\helpers;

class FormatHelper
{
    public const SECONDS_PER_MINUTE = 60;
    public const SECONDS_PER_HOUR = self::SECONDS_PER_MINUTE * 60;
    public const SECONDS_PER_DAY = self::SECONDS_PER_HOUR * 24;
    public const SECONDS_PER_WEEK = self::SECONDS_PER_DAY * 7;
    public const SECONDS_PER_MONTH = self::SECONDS_PER_DAY * 30;
    public const SECONDS_PER_YEAR = self::SECONDS_PER_MONTH * 12;

    /**
     * @param string $date
     * @return string
     */
    public static function getRelativeTime(string $date): string
    {

        if (!strtotime($date)) {
            return '';
        }

        $array = [
            [self::SECONDS_PER_MINUTE, 1, 'секунда', 'секунды', 'секунд'],
            [self::SECONDS_PER_HOUR, self::SECONDS_PER_MINUTE, 'минута', 'минуты', 'минут'],
            [self::SECONDS_PER_DAY, self::SECONDS_PER_HOUR, 'час', 'часа', 'часов'],
            [self::SECONDS_PER_WEEK, self::SECONDS_PER_DAY, 'день', 'дня', 'дней'],
            [self::SECONDS_PER_MONTH, self::SECONDS_PER_WEEK, 'неделя', 'недели', 'недель'],
            [self::SECONDS_PER_YEAR, self::SECONDS_PER_DAY * 30, 'месяц', 'месяца', 'месяцев'],
            [PHP_INT_MAX, self::SECONDS_PER_YEAR, 'год', 'года', 'лет']
        ];

        $ts_diff = time() - strtotime($date);

        $i = 0;
        do {
            $time = floor($ts_diff / $array[$i][1]);
            $relative_time = "$time " . self::getNounPluralForm($time, $array[$i][2], $array[$i][3], $array[$i][4]);
            $i++;

            if ($ts_diff < $array[$i - 1][0]) {
                break;
            }
        } while ($i < count($array));

        return $relative_time;
    }

    /**
     * @return string
     */
    public static function getNounPluralForm(int $number, string $one, string $two, string $many): string
    {
        $number = (int)$number;
        $mod10 = $number % 10;
        $mod100 = $number % 100;

        switch (true) {
            case ($mod100 >= 11 && $mod100 <= 20):
                return $many;

            case ($mod10 > 5):
                return $many;

            case ($mod10 === 1):
                return $one;

            case ($mod10 >= 2 && $mod10 <= 4):
                return $two;

            default:
                return $many;
        }
    }
}
