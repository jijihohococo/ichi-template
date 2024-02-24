<?php

namespace JiJiHoHoCoCo\IchiTemplate\Component;

use JiJiHoHoCoCo\IchiTemplate\Traits\BasePath;

class ComponentSetting
{

	use BasePath;

	public static function setPath(string $path)
	{
		self::$path = substr($path, 0, -1) !== '\\' ? $path . '\\' : $path;
	}
}