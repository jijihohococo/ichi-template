<?php

namespace JiJiHoHoCoCo\IchiTemplate\Traits;

trait BasePath
{
    private static $path;

    public static function getPath()
    {
        return self::$path;
    }
}
