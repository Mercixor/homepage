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
$button = new \libs\menuitems\Button('','buttonTest');
$button->setContent('Testbutton');
$mainDiv = new \libs\menuitems\DivContainer();
$mainDiv->addChild($button);
$nextButton = new \libs\menuitems\Button('','unterButton');
$nextButton->setContent('Destroy');
$secondDiv = new \libs\menuitems\DivContainer('unter');
$secondDiv->addChild($nextButton);
$mainDiv->addChild($secondDiv);

// Building the page
$mainpage = new MainpageFactory();
$mainpage->setContent($mainDiv->buildHtml());
echo $mainpage->getHtml();

