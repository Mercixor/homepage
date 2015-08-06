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
            $className = str_replace($this->namespace. '\\', '', $className);
        }

        $className = str_replace($this->namespace . '\\', DIRECTORY_SEPARATOR, $className );

        $file = ROOT_PATH . $className. '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
