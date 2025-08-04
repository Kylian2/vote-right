<?php

require_once("models/file.php");

class FileController
{

    public static function save()
    {

        if (!isset($_POST['name'])) {
            $file = File::save();
            echo json_encode($file);
            return;
        }

        $file = File::save($_POST['name']);
        echo json_encode($file);
    }
}
