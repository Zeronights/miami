<?php

namespace Miami\Core;

class View extends Cms {
	
	protected $view;
	protected $values = array();
	protected $key;
	protected $val;
	protected $templates = array();
	
	const TPL_PRE = 2;
	const TPL_POST = 4;
	
	public function __construct($view = null) {
		$this->view = $view;
		return $this;
	}
	
	public function set_view($view) {
		$this->view = $view;
		return $this;
	}
	
	public function set_value($key, $val) {
		$this->values[$key] = $val;
		return $this;
	}
	
	public function set_values($values) {
		foreach ($values as $key => $val) {
			$this->values[$key] = $val;
		}
		return $this;
	}
	
	public function unset_value($key) {
		unset($this->values[$key]);
		return $this;
	}
	
	public function unset_values($values) {
		foreach ($values as $key => $val) {
			unset($this->values[$key]);
		}
		return $this;
	}
	
	public function set_template($alias, $view, $type = self::TPL_POST) {
		$this->templates[$alias] = array(
			'view' => $view,
			'type' => $type,
			'values' => array()
		);
		return $this;
	}
	
	public function set_template_value($alias, $key, $value) {
		if (isset($this->templates[$alias])) {
			$this->templates[$alias]['values'][$key] = $value;
		}
		return $this;
	}
	
	public function set_template_values($alias, $values) {	
		if (isset($this->templates[$alias])) {
			foreach ($values as $key => $value) {
				$this->templates[$alias]['values'][$key] = $value;
			}
		}
		return $this;
	}
	
	public function render() {
		
		$templates = array(
			self::TPL_PRE => array(),
			self::TPL_POST => array()
		);
		
		if (!empty($this->templates)) {
			
			foreach ($this->templates as $template) {
				
				switch ($template['type']) {
					case self::TPL_PRE:
					case self::TPL_POST:
						$class = new static($template['view']);
						$templates[$template['type']][] = $class
							->set_values($template['values'])
							->render();
						break;
				}
			}
		}
		
		ob_start();
		echo implode('', $templates[self::TPL_PRE]);
		foreach ($this->values as $this->key => $this->value) {
			${$this->key} = $this->value;
		}
		if ($this->view) {
			$load = Load::get_instance();
			include $load->path('Apps/' . Cms::get_current_app() . '/view/' . $this->view);
		}
		echo implode('', $templates[self::TPL_POST]);
		
		return ob_get_clean();
	}
	
	public static function load($view, $values = array()) {
		$view = new static($view);
		return $view->set_values($values)->render();
	}
}