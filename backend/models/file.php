<?php

require_once("core/sessionGuard.php");

class File extends Model
{

    public int $FIL_id_NB;
    public string $FIL_name_VC;
    public string $FIL_path_VC;
    public string $FIL_type_VC;
    public int $FIL_user_NB;

    public static function save(string $array_name, string | null $target_name = null)
    {

        if (!isset($_FILES[$array_name])) {
            return false;
        }

        $target_dir = "uploads/";
        $file = $target_dir . basename($_FILES[$array_name]["name"]);
        $target_file_type = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($target_name && is_string($target_name) && trim($target_name) !== '') {
            $target_name = trim($target_name);
            if (strlen($target_name) > 100) {
                throw new InvalidArgumentException("Le nom est trop long");
            }
            $target_name = filter_var($target_name, FILTER_UNSAFE_RAW);

            if (!preg_match('/^[a-zA-Z0-9._-]+$/', $target_name)) {
                throw new InvalidArgumentException("Caractères non autorisés dans le nom");
            }
        }

        $id = uniqid();
        $file_name = $target_name ?? 'fichier_' . $id;
        $target_file = $target_dir . 'fichier_' . $id . '.' . $target_file_type;

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES[$array_name]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
            } else {
                return false;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            return false;
        }

        // Check file size
        if ($_FILES[$array_name]["size"] > 500000) {
            return false;
        }

        // Allow certain file formats, currently only images
        if (
            $target_file_type != "jpg" && $target_file_type != "png" && $target_file_type != "jpeg"
        ) return false;

        move_uploaded_file($_FILES[$array_name]["tmp_name"], $target_file) ? $target_file : false;

        $values = array(
            "FIL_name_VC" => $file_name,
            "FIL_type_VC" => $target_file_type,
            "FIL_path_VC" => $target_file,
            "FIL_user_NB" => SessionGuard::getUserId()
        );

        $file_instance = new File($values);
        $file_instance->insert();

        return $file_instance;
    }

    public function insert()
    {

        $request = 'INSERT INTO file(FIL_name_VC, FIL_path_VC, FIL_type_VC, FIL_user_NB) VALUES (:file_name, :file_path, :file_type, :file_user)';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "file_name" => $this->FIL_name_VC,
            "file_path" => $this->FIL_path_VC,
            "file_type" => $this->FIL_type_VC,
            "file_user" => $this->FIL_user_NB,
        );

        $prepare->execute($values);

        $id = connexion::pdo()->lastInsertId();
        $this->set('FIL_id_NB', $id);
    }
}
