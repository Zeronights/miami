<?php

namespace Miami\Classes\Form;

class FormException extends \Exception {
	protected $fields = array();
	
	public function __construct(array $fields = array()) {
		foreach ($fields as $field => $options) {
			
		}
	}
	
	public function get_fields() {
		return $fields;
	}
}