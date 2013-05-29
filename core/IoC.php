<?php

namespace Miami\Core;

/**
 * @todo Smart loading via construct
 */
class IoC {
	
	protected static $containers = array();
	
	public static function register($name, \Closure $callback = null) {
		static::$containers[$name] = (object) array(
			'callback' => $callback,
			'object' => null,
			'singleton' => false
		);
	}
	
	public static function singleton($name, \Closure $callback = null) {
		static::register($name, $callback);
		static::$containers[$name]->singleton = true;
	}
	
	public static function resolve($name) {
		if (!isset(static::$containers[$name])) {
			// Throw exception
			return;
		}
		if (is_object(static::$containers[$name]->object)
			&& static::$containers[$name]->singleton) {
			return static::$containers[$name]->object;
		}
		if (is_callable(static::$containers[$name]->callback)) {
			$callback = static::$containers[$name]->callback;
			static::$containers[$name]->object = $callback();		
		} else {
			static::$containers[$name]->object = new $name();
		}
		return static::$containers[$name]->object;
	}
}