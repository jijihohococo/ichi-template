<?php

namespace JiJiHoHoCoCo\IchiTemplate\Template;

use JiJiHoHoCoCo\IchiTemplate\Traits\BasePath;

use Exception;

class View
{

	use BasePath;

	private static $errors = [];
	private static $success = [];
	private static $share = [];
	private static $numberOfStations = 0;
	private static $currentSection;
	private static $startSection;
	private static $sections = [];
	private static $extend;
	private static $extendData = [];
	private static $title;

	public static function setPath(string $path)
	{
		if (substr($path, -1) !== '/') {
			$path = $path . '/';
		}
		self::$path = $path;
	}

	public static function setErrors(array $errors)
	{
		self::$errors = $errors;
	}

	public static function setSuccess(array $success)
	{
		self::$success = $success;
	}

	public static function getTitle()
	{
		return self::$title;
	}

	public static function share(array $share)
	{
		self::$share = $share;
	}

	private static function checkRequest(array $data)
	{
		try {
			$checkUnique = array_intersect_key($_REQUEST, $data);
			if (!empty($checkUnique)) {
				throw new Exception("You can't set request key into render function", 1);
			}
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

	public static function render(string $view, array $data = [])
	{
		try {
			$view = getViewPath($view);
			$path = self::getPath();

			$errors = [];
			$success = [];
			if (!empty(self::$errors)) {
				$errors = self::$errors;
			}
			if (!empty(self::$success)) {
				$success = self::$success;
			}
			if (!empty($data)) {
				self::checkRequest($data);
				extract($data);
			}
			if (!empty(self::$share)) {
				self::checkRequest(self::$share);
				extract(self::$share);
			}
			if (file_exists($path . $view) && checkPHP($view)) {
				require $path . $view;
			} else {
				throw new Exception($path . $view . ' is not exist', 1);
			}
			if (self::$extend !== NULL) {
				$extend = self::$extend;
				$extendData = self::$extendData;
				self::$extend = NULL;
				self::$extendData = [];
				return self::render($extend, $extendData);
			}
			return TRUE;
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

	public static function section(string $content, string $title = NULL)
	{
		ob_start();
		self::$numberOfStations++;
		self::$currentSection = self::$numberOfStations;
		self::$startSection[self::$currentSection] = $content;
		self::$title = $title;
	}

	public static function endSection()
	{
		try {

			$startSection = self::$startSection;
			$currentSection = self::$currentSection;
			if (empty($startSection) || !isset($startSection[$currentSection])) {
				throw new Exception("Please start section firstly", 1);
			}
			if (self::$numberOfStations - 1 > 1) {
				self::$numberOfStations--;
				self::$currentSection = self::$numberOfStations;
			}
			if (self::$numberOfStations == 1) {
				self::$currentSection = NULL;
				self::$numberOfStations = 0;
			}
			self::$sections[$startSection[$currentSection]] = ob_get_contents();
			unset(self::$startSection[$currentSection]);
			ob_end_clean();
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

	public static function extend(string $file, array $data = [])
	{
		self::$extend = getViewPath($file);
		self::$extendData = $data;
	}

	public static function yieldContent(string $content)
	{
		if (isset(self::$sections[$content])) {
			echo self::$sections[$content];
		}
	}

	public static function include(string $view, array $data = [])
	{
		try {
			$view = getViewPath($view);
			$path = self::getPath();
			if (!file_exists($path . $view)) {
				throw new Exception($path . $view . ' is not exist', 1);
			}
			if (!empty($data)) {
				self::checkRequest($data);
				extract($data);
			}
			if (file_exists($path . $view)) {
				include $path . $view;
			}
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

	public static function includeOnce(string $view, array $data = [])
	{
		try {
			$view = getViewPath($view);
			$path = self::getPath();
			if (!file_exists($path . $view)) {
				throw new Exception($path . $view . ' is not exist', 1);
			}
			if (!empty($data)) {
				self::checkRequest($data);
				extract($data);
			}
			if (file_exists($path . $view)) {
				include_once $path . $view;
			}
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

	public static function require(string $view, array $data = [])
	{
		try {
			$view = getViewPath($view);
			$path = self::getPath();
			if (!file_exists($path . $view)) {
				throw new Exception($path . $view . ' is not exist', 1);
			}
			if (!empty($data)) {
				self::checkRequest($data);
				extract($data);
			}
			if (file_exists($path . $view)) {
				require $path . $view;
			}
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

	public static function requireOnce(string $view, array $data = [])
	{
		try {
			$view = getViewPath($view);
			$path = self::getPath();
			if (!file_exists($path . $view)) {
				throw new Exception($path . $view . ' is not exist', 1);
			}
			if (!empty($data)) {
				self::checkRequest($data);
				extract($data);
			}
			if (file_exists($path . $view)) {
				require_once $path . $view;
			}
		} catch (Exception $e) {
			return showErrorPage($e->getMessage());
		}
	}

}