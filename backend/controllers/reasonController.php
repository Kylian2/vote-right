<?php

require_once("models/reason.php");

class reasonController{

    public static function index(){
        $reasons = Reason::getReasons();
        echo json_encode($reasons);
    }

}

?>