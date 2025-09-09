<?php

require_once("models/file.php");

class FileController
{

    public static function save()
    {

        if (!isset($_POST['name'])) {
            $file = File::save('fileToUpload');
            echo json_encode($file);
            return;
        }

        $file = File::save('fileToUpload', $_POST['name']);
        echo json_encode($file);
    }

    public static function saveUserFile()
    {
        if (!isset($_POST['name'])) {
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing filename';
            echo json_encode($return);
            return;
        }

        $file = File::saveWithPath('image', $_POST['name']);
        echo json_encode($file);
    }

    public static function getAll(array $params)
    {
        $files = File::getAllOf($params[0]);
        echo json_encode($files);
    }

    public static function getMines()
    {
        $files = File::getAllOf(SessionGuard::getUserId());
        echo json_encode($files);
    }
}
