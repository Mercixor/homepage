<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 15.07.2015
 * Time: 22:17
 */

namespace core;


class Autoloader {
    private $namespace;

    public function  __construct( $namespace = null) {
        $this->namespace = $namespace;
    }

    public function register() {
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function loadClass( $className ) {
        if ( $this->namespace !== null ) {
            $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        }

        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className );

        $file = PATH_ROOT . $className. '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
