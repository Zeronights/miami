<?php

namespace Apps\Admin\Classes;

use \Miami\Core\Config;

class Controller extends \Miami\Core\Controller {
	
	protected $view = null;
	protected $auth = null;
	protected $route = null;
	protected $database = null;
	
	public function __construct() {
		$conf = Config::get_instance();
		$conf_options = $conf->get('options');
		$conf_database = $conf->get('database')
			->connections
			->{$conf->get('database')->default_connection};
		
		$this->view = new \Miami\Core\View();
		$this->auth = new \Miami\Core\Auth();
		$this->auth->set_salt($conf_options->auth->salt);
		$this->route = \Miami\Core\Route::get_instance();
		
		$this->database = new \Miami\Core\Database(array(
			'driver' => $conf_database->driver,
			'host' => $conf_database->host,
			'port' => $conf_database->port,
			'username' => $conf_database->username,
			'password' => $conf_database->password,
			'database' => $conf_database->database
		));
	}
}