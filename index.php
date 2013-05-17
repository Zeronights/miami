<?php

if (!defined('DIR_ROOT')) {
	define('DIR_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

require_once 'core/Singleton.php';
require_once 'core/Load.php';

use Miami\Core\Load;
use Miami\Core\Config;

$load = Load::get_instance();
spl_autoload_register(array($load, 'autoload'));
$load->register('Apps\Admin', 'admin');
$load->register('Miami', '');

$config = Config::get_instance();


// Create options config
$config->load('options', array(
	'current_app' => 'admin',
	'app' => 'miami',
	'auth' => (object) array(
		'salt' => '43h28dhskh'
	)
));


// Temporary database config, will load from app
$config->load('database', array(
	'default_connection' => 'miami',
	'connections' => (object) array(
		'miami' => (object) array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'port' => 3306,
			'username' => 'root',
			'password' => '',
			'database' => 'miami'
		)
	)
));


var_dump('Sort out load and register namespace.');
$login = new \Apps\Admin\Controller\Login();
echo($login->action_index()->render());

// Register namespaces
#$load->register('\Miami', 'extra/sing_le');
#$load->autoload('\Miami\Core\Test\Abstract_Single');