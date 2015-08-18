<?php
/**
 * @author Dennis Jandt
 */
class Sanitize {
    
    public static function xss_clean( $string ) {
        return mysqli_real_escape_string($string);
    }
}
