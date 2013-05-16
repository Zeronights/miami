<?php

namespace Miami\Core;

class Controller {
		
	protected function load_model($model, $alias = null) {
		$model_full = '\Apps\\' . Cms::get_current_app() . '\Model\\' . $model;
		$this->{$alias ? $alias : $model} = new $model_full;
	}
}