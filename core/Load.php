<?php

namespace Miami\Core;

class Load extends Singleton {

	protected $registered_classes = array();
	
	public function __construct() {

	}

	public function path($path, $options = array()) {
		
		$options = $options + array(
			'include' => false
		);
		$path = $this->resolve($path, $options);
		
		if ($options['include']) {
			include $path;
		} else {
			return $path;
		}
	}
	
	public function autoload($class) {
		$path = $this->resolve($class);
		require_once $path;
	}
	
	public function register($class, $path) {
		$this->registered_classes[$class] = $path;
	}
	
	protected function resolve($path, array $options = array()) {
			
		$options = $options + array(
			'underscore' => true,
			'register' => true,
			'file' => false,
			'ext' => '.php'
		);
		$options = (object) $options;
		$dir_regex = '/[\/\\\]+/';
		$path = preg_replace($dir_regex, DIRECTORY_SEPARATOR, $path);
		
		if ($options->file) {
			$path = trim($path, DIRECTORY_SEPARATOR);
		}
		
		if ($options->register) {
			$path = ltrim($path, DIRECTORY_SEPARATOR);
			foreach ($this->registered_classes as $class => $class_path) {
				$class = preg_replace($dir_regex, DIRECTORY_SEPARATOR, $class);
				
				if (stripos($path, $class) === 0) {

					$path = $class_path . substr($path, strlen($class));
					$path = preg_replace($dir_regex, DIRECTORY_SEPARATOR, $path);
					break;
				}
			}
		}
		
		$folders = dirname(trim($path, DIRECTORY_SEPARATOR));
		$file = basename($path);
		
		if ($options->underscore) {
			$pos = strrpos($file, '_');
			
			if ($pos !== false) {				
				$folders .=  DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, substr($file, 0, $pos));
				$file = substr($file, $pos + 1);
			}
		}
		
		$folders = strtolower($folders);
		return DIR_ROOT . $folders . DIRECTORY_SEPARATOR . $file . $options->ext;
	}
}