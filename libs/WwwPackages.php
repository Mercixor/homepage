<?php
namespace libs;

class WwwPackages {

	const SERVER_PATH = '';
	protected $homeServer;
    
    public static function loadPackages( $json_name ) {
        $filePath = PATH_PACKAGES . $json_name . '.json';
        // check if package name exist
        if( file_exists($filePath) ) {
            $jsonString = file_get_contents($filePath);
            $jsonArray = json_decode($jsonString, true, 20);
            // check if found file is array
            if(is_array($jsonArray)) {
                self::includePackage($jsonArray);
            }
        }
    }

    protected static function includePackage( $jsonArray ) {
        foreach ($jsonArray as $key => $entry) {
            // Iterate over json array
            switch ($key) {
                case 'packages':
                     // load package if required
                    foreach ($entry as $package) {
                        self::loadPackages($package);
                    }
                    break;
                case 'js':
                    // load js files
                    foreach($entry AS $js_file) {
                        $filePath = self::SERVER_PATH . '/www/js/' . $js_file;
						//$filePath = PATH_JS . $js_file;
                        echo '<script type="text/javascript" charset="utf-8" src="'.$filePath.'"></script>';
                    }
                    break;
                case 'css':
                    // load css files
                    foreach($entry AS $css_file) {
                        $filePath = self::SERVER_PATH . '/www/css/' . $css_file;
						//$filePath = PATH_CSS . $css_file;
                        echo '<link rel="stylesheet" type="text/css" href="'.$filePath.'">';
                    }
                    break;
                case 'templates':
                    // load template files
                    foreach ($entry as $template) {
                        $filePath = PATH_TPL . $template;
                        if(file_exists($filePath)) {
                            include_once $filePath;
                        }
                    }
            }
        }
    }
}
