<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 15.07.2015
 * Time: 22:26
 */

use core\Autoloader;

define( 'ROOT_PATH', dirname( dirname(__FILE__) ) . DIRECTORY_SEPARATOR);
define( 'AJAX_PATH', ROOT_PATH .'ajax' . DIRECTORY_SEPARATOR );
define( 'LIB_PATH', ROOT_PATH .'lib'. DIRECTORY_SEPARATOR);
define( 'CORE_PATH', ROOT_PATH .'core'. DIRECTORY_SEPARATOR);

require_once CORE_PATH . 'Autoloader.php';

$autoloader = new Autoloader('htdocs');
$autoloader->register();
