<?php
use libs\MainpageFactory;
use ajax\AjaxHandler;

require_once 'config\bootstrap.php';

if(isset($_GET['action'])) {
    $ajax = new AjaxHandler();
    $response = $ajax->processRequest($_GET['action']);
    die();
}


// Building the Content


// Building the page
$mainpage = new MainpageFactory();
echo $mainpage->getHtml();

