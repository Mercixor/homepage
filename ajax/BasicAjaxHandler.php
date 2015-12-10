<?php
namespace ajax;

class BasicAjaxHandler {

    public function processRequest($action) {
        if (method_exists($this, $action)){
            $respone = $this->$action();
        } else {
            return;
        }
        header('Content-Type: application/json');

        echo json_encode($respone);
    }
}
