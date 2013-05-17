<?php

namespace Miami\Core;

class Config extends Singleton {
	
	protected $configs = array();
	
	public function get($config) {
		if (isset($this->configs[$config])) {
			return $this->configs[$config];
		}
		$load = Load::get_instance();
		$config_path = $load->path('\App\\' . Cms::get_current_app() . '\Config\\' . $config);
		return $this->configs[$config] = (object) (include $config_path);
	}
	
	public function load($config, array $array) {
		$this->configs[$config] = (object) $array;
	}
}
