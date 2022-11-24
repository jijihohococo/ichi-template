<?php

use JiJiHoHoCoCo\IchiTemplate\Template\View;
use JiJiHoHoCoCo\IchiTemplate\Component\{Component,ComponentSetting};

if(!function_exists('getViewPath')){
	function getViewPath(string $view){
		$viewArray=explode('.', $view);
		$countViewArray=count($viewArray);
		return $viewArray[$countViewArray-1]=='php' ? $view : $view . '.php';
	}
}

if(!function_exists('checkPHP')){
	function checkPHP(string $file){
		$pathInfo = pathinfo($file);
		return $pathInfo['extension']=='php';
	}
}

if(!function_exists('view')){
	function view(string $view,array $data=[]){
		return View::render($view,$data);
	}
}

if(!function_exists('includeView')){
	function includeView(string $view,array $data=[]){
		View::include($view,$data);
	}
}

if(!function_exists('includeOnceView')){
	function includeOnceView(string $view,array $data=[]){
		View::includeOnce($view,$data);
	}
}

if(!function_exists('requireView')){
	function requireView(string $view,array $data=[]){
		View::require($view,$data);
	}
}

if(!function_exists('requireOnceView')){
	function requireOnceView(string $view,array $data=[]){
		View::requireOnce($view,$data);
	}
}

if(!function_exists('old')){
	function old($data,$default=null){

		echo isset($_REQUEST[$data]) ? $_REQUEST[$data] : e($default);
	}
}

if(!function_exists('e')){
	function e(string $data=NULL){
		return $data==NULL ? $data : htmlspecialchars($data, ENT_QUOTES);
	}
}

if(!function_exists('getPath')){
	function getPath(){
		return View::getPath();
	}
}

if(!function_exists('section')){
	function section(string $content,string $title=NULL){
		View::section($content,$title);
	}
}

if(!function_exists('endSection')){
	function endSection(){
		View::endSection();
	}
}

if(!function_exists('y')){
	function y(string $content){
		View::yieldContent($content);
	}
}

if(!function_exists('extend')){
	function extend(string $file){
		View::extend($file);
	}
}

if(!function_exists('title')){
	function title(){
		return View::getTitle();
	}
}

if(!function_exists('component')){
	function component(string $class,array $data=[]){
		$class=ComponentSetting::getPath().$class;
		if(class_exists($class)){
			$reflectionClass=new ReflectionClass($class);
			$constructor=$reflectionClass->getConstructor();
			$newParameters=[];
			if($constructor==NULL && !empty($data)){
				throw new Exception("Your component don't have argument(s) to put in", 1);
			}elseif($constructor!==NULL && !empty($constructor->getParameters()) &&  empty($data) ){
				throw new Exception("Please add constructor argument(s) for your Component", 1);
			}elseif($constructor==NULL && empty($constructor->getParameters()) && empty($data)){
				$newParameters=$data;
			}elseif($constructor!==NULL){
				$parameters=$constructor->getParameters();
				if(count($parameters)==count($data)){

					foreach ($parameters as $key => $parameter) {
						if($parameter->name!==NULL && isset($data[$parameter->name])){
							$newParameters[$key]=$data[$parameter->name];
						}
					}
					if(count($parameters)!==count($newParameters)){
						throw new Exception("Parameters are not same", 1);
						
					}
				}else{
					throw new Exception("Parameters are not same", 1);	
				}
			}
			ksort($newParameters);
			$newClass=$reflectionClass->newInstanceArgs($newParameters);
			if($newClass instanceof Component && method_exists($newClass,'render')){
				return $newClass->render($newParameters);
			}else{
				throw new Exception("You need to extend JiJiHoHoCoCo\IchiTemplate\Component\Component and include render function in your {$class}", 1);
			}
		}else{
			throw new Exception("Class is not exists", 1);
		}
	}
}

if(!function_exists('setErrors')){
	function setErrors(array $errors){
		View::setErrors($errors);
	}
}

if(!function_exists('setSuccess')){
	function setSuccess(array $success){
		View::setSuccess($success);
	}
}