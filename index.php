<?php

if (!defined('DIR_ROOT')) {
	
	define('DIR_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

require_once 'core/Singleton.php';
require_once 'core/Load.php';

use Miami\Core\Load;

$load = Load::get_instance();

spl_autoload_register(array($load, 'autoload'));


// Register namespaces
#$load->register('\Miami', 'extra/sing_le');
#$load->autoload('\Miami\Core\Test\Abstract_Single');

