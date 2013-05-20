<?php

if (!defined('DIR_ROOT')) {
	define('DIR_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

require 'core/Singleton.php';
require 'core/Load.php';

use Miami\Core\Load;

function aassert($test, $answer) {
	
	return $test == $answer ? 'Passed' : 'Failed';
}

$load = new Load();


var_dump($load->path('\Apps\Admin\Test'));

$load->register('Miami', 'test\Admain_asdaJ');

var_dump($load->path('Miami'));