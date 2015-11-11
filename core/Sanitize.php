<?php
namespace core;
use libs\Database;

class Sanitize {

    public static function xss_clean( $string ) {
        $mysqli = Database::getInstance();
        $escaped = $mysqli->escapeSqlString($string);
        return $escaped;
    }

    public static function generate_code( $length ) {
        $intArray = range(0,9);
        $charArrayLower = range('a', 'z');
        $charArrayUpper = range('A', 'Z');

        $keyArray = array_merge($charArrayLower, $intArray, $charArrayUpper);

        $hash = '';
        for($i=0;$i<$length;$i++) {
            $number = rand(0, 62);
            $hash .= $keyArray[$number];
        }
        return $hash;
    }
}
