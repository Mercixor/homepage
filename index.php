<?php
use libs\MainpageFactory;
use ajax\AjaxHandler;

require_once 'config'.DIRECTORY_SEPARATOR.'bootstrap.php';

if(isset($_GET['action'])) {
    $ajax = new AjaxHandler();
    $response = $ajax->processRequest($_GET['action']);
    die();
}
// check if content for module.php

include_once PATH_LIB . 'database_constants.php';

// Building the Content


// Building the page
$mainpage = new MainpageFactory();
echo $mainpage->getHtml();

