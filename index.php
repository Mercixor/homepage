<?php
use \libs;
use \ajax;

if(isset($_GET['action'])) {
	$ajax = new AjaxHandler();
	$response = $ajax->processRequest($_GET['action']);
	die();
}

// Building the page
$mainpage = new MainpageFactory();
echo $mainpage->getHtml();

