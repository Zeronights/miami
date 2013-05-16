<?php

namespace Miami\Core;

class Singleton {
	
	protected static $instances = array();
	
	public static function get_instance() {
		
		$class = get_called_class();
		
		if (isset(self::$instances[$class])) {
			return self::$instances[$class];
		}
		
		$reflect = new \ReflectionClass($class);
		$args = func_get_args();
		return self::$instances[$class] = $reflect->newInstanceArgs($args);
	}
}