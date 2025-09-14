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

    public static function update(array $params)
    {
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if (empty($body) || !isset($body["name"])) {
            http_response_code(422);
            echo '{"Unprocessable Entity":"at least one field is required"}';
            return;
        }

        $fieldsToUpdate = array();
        $file = new File();

        if (isset($body["name"]) && $body["name"] !== "" && strlen($body["name"]) <= 100) {
            $fieldsToUpdate[] = 'name';
            $file->FIL_name_VC = $body["name"];
        }

        $file->update($params[0], $fieldsToUpdate);
    }

    public static function delete(array $params)
    {
        echo File::delete($params[0]);
    }
}
