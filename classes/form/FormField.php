<?php

namespace Miami\Classes\Form;

class FormField {
	protected $value = null;
	protected $name = null;
	protected $prefix = null;
	protected $required = false;
	
	public function __construct($options) {
		$this->value = $options->value;
		$this->name = $options->name;
		$this->prefix = $options->prefix;
		$this->required = $options->required;
	}
	
	public function get_name() { return $this->name; }
	public function get_full_name() { return $this->prefix . $this->name; }
	public function get_prefix() { return $this->prefix; }
	public function get_value() { return $this->value; }
	public function is_required() { return $this->required; }
}