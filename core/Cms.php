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
	
	public function uri($uri = null) {
		$app = static::get_current_app() == static::get_app()
			? '/'
			: '/' . static::get_current_app() . '/';
		return $app . $uri;
	}
	
	public function base($uri = null) {
		return '/' . static::get_current_app() . '/www/' . $uri;
	}
	
	public function text($text) {
		return $text;
	}
	
	public function nav($nav) {
		return $nav;
	}
}