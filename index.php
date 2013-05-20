<?php

ini_set('short_open_tag', 'On');

if (!defined('DIR_ROOT')) {
	define('DIR_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

require_once 'core/Singleton.php';
require_once 'core/Load.php';