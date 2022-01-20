<?php

namespace JiJiHoHoCoCo\IchiTemplate\Template;

use JiJiHoHoCoCo\IchiTemplate\Trait\BasePath;

use Exception;
class View{

	use BasePath;

	private static $errors=[];
	private static $success=[];
	private static $share=[];
	private static $numberOfStations=0;
	private static $currentSection;
	private static $startSection;
	private static $sections=[];
	private static $extend;
	private static $extendData=[];
	private static $title;

	public static function setPath(string $path){
		if(substr($path, -1)!=='/'){
			$path=$path.'/';
		}
		self::$path=$path;
	}

	public static function setErrors(array $errors){
		self::$errors=$errors;
	}

	public static function setSuccess(array $success){
		self::$success=$success;
	}

	public static function getTitle(){
		return self::$title;
	}

	public static function share(array $share){
		self::$share=$share;
	}

	private static function checkRequest(array $data){
		$checkUnique=array_intersect_key($_REQUEST, $data);
		if(!empty($checkUnique)){
			throw new \Exception("You can't set request key into render function", 1);
		}
	}

	public static function render(string $view,array $data=[]){
		$path=self::getPath();
		
		$errors=[];
		if(!empty(self::$errors)){
			$errors=self::$errors;
		}
		if(!empty(self::$success)){
			$success=self::$success;
		}
		self::showRender($data);
		self::showRender(self::$share);
		if(file_exists($path.$view) && checkPHP($view) ){
			require $path . $view;
		}else{
			throw new Exception($path.$view.' is not exist', 1);
		}
		if(self::$extend!==NULL){
			$extend=self::$extend;
			$extendData=self::$extendData;
			self::$extend=NULL;
			self::$extendData=[];
			self::render($extend,$extendData);
		}
	}

	public static function section(string $content,string $title=NULL){
		ob_start();
		self::$numberOfStations++;
		self::$currentSection=self::$numberOfStations;
		self::$startSection[self::$currentSection]=$content;
		self::$title=$title;
	}

	public static function endSection(){

		$startSection=self::$startSection;
		$currentSection=self::$currentSection;
		if(empty($startSection) || !isset($startSection[$currentSection]) ){
			throw new Exception("Please start section firstly", 1);
		}
		if(self::$numberOfStations-1>1){
			self::$numberOfStations--;
			self::$currentSection=self::$numberOfStations;
		}elseif(self::$numberOfStations==1){
			self::$currentSection=NULL;
			self::$numberOfStations=0;
		}
		self::$sections[$startSection[$currentSection]]=ob_get_contents();
		unset(self::$startSection[$currentSection]);
		ob_end_clean();
	}

	public static function extend(string $file,array $data=[]){
		self::$extend=$file;
		self::$extendData=$data;
	}

	public static function yieldContent(string $content){
		if(isset(self::$sections[$content])){
			echo self::$sections[$content];
		}
	}

	private static function showRender(array $data=[]){
		if(!empty($data) ){
			self::checkRequest($data);
			extract($data);
		}
	}

	public static function include(string $view,array $data=[]){
		$path=self::getPath();
		self::showRender($data);
		if(file_exists($path.$view)){
			include $path . $view;
		}else{
			throw new Exception($path.$view.' is not exist', 1);
		}
	}

	public static function includeOnce(string $view,array $data=[]){
		$path=self::getPath();
		self::showRender($data);
		if(file_exists($path.$view)){
			include_once $path . $view;
		}else{
			throw new Exception($path.$view.' is not exist', 1);
		}
	}

	public static function require(string $view,array $data=[]){
		$path=self::getPath();
		self::showRender($data);
		if(file_exists($path.$view)){
			require $path . $view;
		}else{
			throw new Exception($path.$view.' is not exist', 1);
		}
	}

	public static function requireOnce(string $view,array $data=[]){
		$path=self::getPath();
		self::showRender($data);
		if(file_exists($path.$view)){
			require_once $path . $view;
		}else{
			throw new Exception($path.$view.' is not exist', 1);
		}
	}

}