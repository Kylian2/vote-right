<?php

require_once("models/role.php");

class roleController{

    public static function index(){
        $role = Role::getAll();
        echo json_encode($role);
    }

}

?>