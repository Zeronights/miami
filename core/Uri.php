<?php

namespace Miami\Core;

class Uri {
	
	public static function new_from_http() {
		$uri = '';
		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		} elseif (isset($_SERVER['PATH_INFO'])) {
			$uri = $_SERVER['PATH_INFO'];
		}
		return static::new_from_string($uri);
	}
	
	public static function new_from_array(array $uri) {
		return static::new_from_string(implode('/', $uri));
	}
	
	public static function new_from_string($uri) {
		return new static(explode('/', $uri));
	}
	
	protected $uri = array();
	protected $uri_string = '';
	
	public function __construct(array $uri) {
		$this->uri = array_values(array_filter($uri));
		$this->uri_string = implode('/', $this->uri);
	}
	
	public function get_segment($offset, $default = null) {
		return isset($this->uri[$offset - 1])
			? $this->uri[$offset - 1]
			: $default;
	}
	
	public function get_assoc($offset = 1) {
		$assoc = array();
		for ($i = 0 + --$offset, $j = count($this->uri); $i < $j; ++$i) {
			$assoc[$this->uri[$i]] = isset($this->uri[++$i])
				? $this->uri[$i]
				: null;
		}
		return $assoc;
	}
	
	public function get_adjacent($segment, $left = false) {
		$index = array_search($segment, $this->uri);
		if (is_int($index)) {
			$index = $left ? $index-- : $index++;
			return isset($this->uri[$index])
				? $this->uri[$index]
				: null;
		}
	}
	
	public function to_string() {
		return $this->uri_string;
	}
	
	public function to_array() {
		return $this->uri;
	}
}