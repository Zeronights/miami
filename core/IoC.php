<?php

namespace Miami\Core;

class IoC {
	
	protected $containers = array();
	
	public static function register($name, $callback) {
		static::$containers[$name] = (object) array(
			'callback' => $callback,
			'object' => null,
			'singleton' => false
		);
	}
	
	public static function singleton($name, $callback) {
		static::$containers[$name] = (object) array(
			'callback' => $callback,
			'object' => null,
			'singleton' => true
		);
	}
	
	public static function resolve($name) {
		if (!isset(static::$containers[$name])) {
			return;
		}
		if (is_object(static::$containers[$name]->object)
			&& static::$containers[$name]->singleton) {
			return static::$containers[$name]->object;
		}
		$callback = static::$containers[$name]->callback;
		static::$containers[$name]->object = $callback();		
		return static::$containers[$name]->object;
	}
}