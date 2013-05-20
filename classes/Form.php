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
		$this->fields[$field] = $options + array('required' => false);
	}
	
	public function set_required_field($field, array $options = array()) {
		$options = array('required' => true) + $options;
		$this->set_field($field, $options);
	}
	
	public function submit(\Closure $callback = null) {
		
		$required_fields = array();
		foreach ($this->fields as $field => $options) {
			$field_name = $this->prefix . $field;
			$data = isset($this->data[$field_name])
				? trim($this->data[$field_name])
				: null;
			if ($options['required'] && strlen($data) < 1) {
				$required_fields[] = new Form\FormField((object) array(
					'name' => $field,
					'prefix' => $this->prefix,
					'value' => null,
					'required' => true
				));
				continue;
			}
			
			if (isset($options['pre_process']) && is_callable($options['pre_process'])) {
				$this->data[$field_name] = $options['pre_process']($data);
			}
		}
		
		if (!empty($required_fields)) {
			throw new Form\FormFieldRequiredException($required_fields);
		}
		
		if (is_callable($callback)) {
			$field_objects = array();
			foreach ($this->fields as $field => $options) {
				$field_name = $this->prefix . $field;
				$field_objects[$field] = new Form\FormField((object) array(
					'name' => $field,
					'prefix' => $this->prefix,
					'value' => trim($this->data[$field_name]),
					'required' => $options['required']
				));
			}
			return $callback((object) $field_objects);
		}
	}
}