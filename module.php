<?php
require_once 'config\bootstrap.php';
// Check if redirect target is set
if( isset($_GET['module']) ) {
    $extensionFile = PATH_CONTENT . $_GET['module'] . '.php';
    if ( file_exists($extensionFile) ) {
        include_once $extensionFile;
    } else {
        echo 'FEHLER - Das Modul existiert nicht.';
    }
    die();
}