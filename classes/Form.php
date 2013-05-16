<?php

namespace Miami\Classes;

class Form {
	
	protected $prefix = null;
	protected $data = array();
	protected $fields = array();
	
	public function __construct($prefix = null) {
		$this->prefix = $prefix;
		$this->data = $_POST;
	}
	
	public function set_prefix($prefix) {
		$this->prefix = $prefix;
	}
	
	public function set_data($data) {
		
	}
	
	public function set_field($field, array $options = array()) {
		
	}
	
	public function set_required_field($field, array $options = array()) {
		
	}
	
	public function submit() {
		
	}
}