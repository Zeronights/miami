<?php

namespace Miami\Core;

class Session extends Singleton {
	
	protected $session_flash;
	
	public function __construct() {
		
		if (!session_id()) {
			session_start();
		}
		
		if (!isset($_SESSION['Miami']['Normal'], $_SESSION['Miami']['Flash'])) {
			$_SESSION['Miami'] = array(
				'Normal' => array(),
				'Flash' => array()
			);
		}
		
		$this->session_flash = $_SESSION['Miami']['Flash'];
		$_SESSION['Miami']['Flash'] = array();		
	}
	
	public function get($key, $default = null) {
		if (isset($_SESSION['Miami']['Normal'][$key])) {
			return $_SESSION['Miami']['Normal'][$key];
		}
	}
	
	public function get_flash($key, $default = null) {
		if (isset($this->session_flash[$key])) {
			return $this->session_flash[$key];
		}	
	}
	
	public function set($key, $value) {
		return $_SESSION['Miami']['Normal'][$key] = $value;		
	}
	
	public function set_flash($key, $value) {
		$this->session_flash[$key] = $value;
		return $_SESSION['Miami']['Flash'][$key] = $value;
	}
}