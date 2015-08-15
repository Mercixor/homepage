<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 15.07.2015
 * Time: 22:26
 */

use core\Autoloader;

define( 'ROOT_PATH'     , dirname( dirname(__FILE__) ) . DIRECTORY_SEPARATOR);
define( 'AJAX_PATH'     , ROOT_PATH . 'ajax'    . DIRECTORY_SEPARATOR);
define( 'CONTENT_PATH'  , ROOT_PATH . 'content' . DIRECTORY_SEPARATOR);
define( 'LIB_PATH'      , ROOT_PATH . 'lib'     . DIRECTORY_SEPARATOR);
define( 'CORE_PATH'     , ROOT_PATH . 'core'    . DIRECTORY_SEPARATOR);
define( 'WWW_PATH'      , ROOT_PATH . 'www'     . DIRECTORY_SEPARATOR);
define( 'ZEND_PATH'     , ROOT_PATH . 'zf2'     . DIRECTORY_SEPARATOR. 'bin' .DIRECTORY_SEPARATOR);
define( 'JS_PATH'       , WWW_PATH  . 'js'          . DIRECTORY_SEPARATOR);
define( 'CSS_PATH'      , WWW_PATH  . 'css'         . DIRECTORY_SEPARATOR);
define( 'TPL_PATH'      , WWW_PATH  . 'templates'   . DIRECTORY_SEPARATOR);


require_once CORE_PATH . 'Autoloader.php';
//require_once ZEND_PATH;

$autoloader = new Autoloader('htdocs');
$autoloader->register();
