<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 15.07.2015
 * Time: 22:26
 */

use core\Autoloader;

define( 'PATH_ROOT'     , dirname( dirname(__FILE__) ) . DIRECTORY_SEPARATOR);
define( 'PATH_AJAX'     , PATH_ROOT . 'ajax'    . DIRECTORY_SEPARATOR);
define( 'PATH_CONTENT'  , PATH_ROOT . 'content' . DIRECTORY_SEPARATOR);
define( 'PATH_LIB'      , PATH_ROOT . 'libs'     . DIRECTORY_SEPARATOR);
define( 'PATH_CORE'     , PATH_ROOT . 'core'    . DIRECTORY_SEPARATOR);
define( 'PATH_WWW'      , PATH_ROOT . 'www'     . DIRECTORY_SEPARATOR);
define( 'PATH_PACKAGES' , PATH_ROOT . 'packages'. DIRECTORY_SEPARATOR);
define( 'PATH_ZEND'     , PATH_ROOT . 'zf2'     . DIRECTORY_SEPARATOR. 'bin' .DIRECTORY_SEPARATOR);
define( 'PATH_JS'       , PATH_WWW  . 'js'          . DIRECTORY_SEPARATOR);
define( 'PATH_CSS'      , PATH_WWW  . 'css'         . DIRECTORY_SEPARATOR);
define( 'PATH_TPL'      , PATH_WWW  . 'templates'   . DIRECTORY_SEPARATOR);



require_once PATH_CORE . 'Autoloader.php';
//require_once ZEND_PATH;

$autoloader = new Autoloader('htdocs');
$autoloader->register();
