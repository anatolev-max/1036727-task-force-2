<?php

namespace anatolev\helpers;

abstract class Helper
{
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments): mixed
    {
        $method = "get{$name}";
        return static::$method($arguments[0]);
    }
}
