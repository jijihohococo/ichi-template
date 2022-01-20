<?php

namespace JiJiHoHoCoCo\IchiTemplate\Trait;

trait BasePath{

	private static $path;

	public static function getPath(){
		return self::$path;
	}
}