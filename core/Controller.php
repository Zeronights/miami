<?php

namespace Miami\Core;

class Controller {
	
	public static function run($controller, $method = null, $params = array()) {
		
		$path = '\Apps\\' . Cms::get_current_app() . '\Controller\\' . $controller;
		$controller = new $path();
		
		if ($method && method_exists($controller, $method)) {

			if (!empty($params)) {				
				$return = call_user_func_array(array($controller, $method), $params);
			} else {				
				$return = $controller->$method();
			}

			if ($return instanceof View) {
				echo $return->render();
			}
		}
	}	
	
	protected function load_model($model, $alias = null) {
		$model_full = '\Apps\\' . Cms::get_current_app() . '\Model\\' . $model;
		$this->{$alias ? $alias : $model} = new $model_full;
	}
}