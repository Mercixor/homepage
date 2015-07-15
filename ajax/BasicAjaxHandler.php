<?php
/**
 * Created by PhpStorm.
 * User: Mercix
 * Date: 13.07.2015
 * Time: 23:36
 */

namespace ajax;


class BasicAjaxHandler
{
    public function processRequest($action) {
        if (method_exists($this, $action)){
            $respone = $this->$action();
        } else {
            return;
        }
        header('Application-Type: json');

        echo json_encode($respone);
    }
}
