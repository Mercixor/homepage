<?php

use ajax\AjaxHandlerLan;
use libs\WwwPackages;

// Auth
if( isset($_REQUEST['hash']) ) {
    $ajax = new AjaxHandlerLan();
    $ajax->authUser();
} else {
    exit();
}

if( isset($_REQUEST['ajax']) ) {
    if (!$ajax) {
        $ajax = new AjaxHandlerLan();
    }
    $ajax->processRequest($_REQUEST['ajax']);
    die();
}

WwwPackages::loadPackages('lan_vorbereitung');
