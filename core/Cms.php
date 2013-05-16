<?php

namespace Miami\Core;

class Cms {
	
	public static function get_current_app() {
		$config = Config::get_instance();
		return $config->get('options')->current_app;
	}
	
	public static function get_app() {
		$config = Config::get_instance();
		return $config->get('options')->app;
	}
}