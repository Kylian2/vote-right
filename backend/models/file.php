<?php

require_once("core/sessionGuard.php");

class File extends Model
{

    public int $FIL_id_NB;
    public string $FIL_name_VC;
    public string $FIL_path_VC;
    public string $FIL_type_VC;
    public int $FIL_user_NB;

    /**
     * Enregistre un fichier (entité + données) dans la base de données
     * 
     * @param string $array_name le nom du paramètre de la requete POST comportant le fichier
     * @param string | null $target_name (facultatif) le nom avec lequel le fichier doit être enregistré
     */
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

    /**
     * Enregistre un fichier (entité + données) dans la base de données et le place dans le fichier associé à l'utilisateur connecté
     * 
     * @param string $array_name le nom du paramètre de la requete POST comportant le fichier
     * @param string $target_name (facultatif) le nom avec lequel le fichier doit être enregistré
     */
    public static function saveWithPath(string $array_name, string $target_name)
    {

        if (!isset($_FILES[$array_name])) {
            return false;
        }

        $target_dir = 'uploads/users/' . SessionGuard::getUserId() . '/';
        $file = $target_dir . basename($_FILES[$array_name]["name"]);
        $target_file_type = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($target_name && is_string($target_name) && trim($target_name) !== '') {
            $target_name = trim($target_name);
            if (strlen($target_name) > 100) {
                throw new InvalidArgumentException("Le nom est trop long");
            }
            $target_name = filter_var($target_name, FILTER_UNSAFE_RAW);
        } else {
        }

        $id = uniqid();
        $target_file = $target_dir . 'fichier_' . $id . '.' . $target_file_type;

        // Check if file already exists
        if (file_exists($target_file)) {
            return false;
        }

        // Check file size
        if ($_FILES[$array_name]["size"] > 1000000) {
            return false;
        }

        // Allow certain file formats, currently only images
        if (
            $target_file_type != "jpg" && $target_file_type != "png" && $target_file_type != "jpeg" && $target_file_type != "pdf" && $target_file_type != "docx"
        ) return false;

        // Check if file extension matches MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES[$array_name]["tmp_name"]);
        finfo_close($finfo);
        $allowed_types = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        if (!in_array($mime_type, $allowed_types)) {
            return false;
        }

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0775, true);
            // 0775 = droits lecture/écriture/exécution pour proprio + groupe
        }

        move_uploaded_file($_FILES[$array_name]["tmp_name"], $target_file);

        $values = array(
            "FIL_name_VC" => $target_name,
            "FIL_type_VC" => $mime_type,
            "FIL_path_VC" => $target_file,
            "FIL_user_NB" => SessionGuard::getUserId()
        );

        $file_instance = new File($values);
        $file_instance->insert();

        return $file_instance;
    }

    /**
     * Insère l'entité en base de donnée. 
     */
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

    /**
     * Récupère tout les fichiers créés par un utilisateur
     * 
     * @param int $user l'utilisateur pour lequel on veut récuperer les fichiers
     * 
     * @return File[] une liste de fichier
     */
    public static function getAllOf(int $user)
    {
        $request = 'SELECT * FROM file WHERE FIL_user_NB = :user ORDER BY created_at DESC';
        $prepare = connexion::pdo()->prepare($request);
        $values['user'] = $user;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "file");
        $files = $prepare->fetchAll();
        return $files;
    }

    /**
     * Mets à jour les informations d'un fichier 
     * 
     * @param int $id l'identifiant du fichier à modifier
     * @param array fieldsToUpdate un tableau contenant la liste des éléments à modifier
     * 
     * @return boolean
     * - 400 avec un message JSON en cas d'erreur lors de la mise à jour d'une donnée.
     * - true (JSON) si la mise à jour réussit.
     */
    public function update(int $id, array $fieldsToUpdate)
    {
        if (empty($fieldsToUpdate)) {
            return false;
        }

        $values = array();
        $setParts = array();

        if (in_array('name', $fieldsToUpdate)) {
            $setParts[] = 'FIL_name_VC = :name';
            $values['name'] = $this->FIL_name_VC;
        }

        $values['id'] = $id;

        echo json_encode($values);

        $request = 'UPDATE file SET ' . implode(', ', $setParts) . ' WHERE FIL_id_NB = :id';
        $prepare = connexion::pdo()->prepare($request);
        try {
            $prepare->execute($values);
        } catch (Exception $e) {
            http_response_code(400);
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return false;
        }
    }

    /**
     * Supprime un fichier
     * 
     * @param int $id l'identifiant du fichier à supprimer
     * 
     * @return boolean
     * - 400 avec un message JSON en cas d'erreur lors de la suppression.
     * - true (JSON) si la suppression réussit.
     */
    public static function delete(int $id)
    {
        $request = 'DELETE FROM file WHERE FIL_id_NB = :id';
        $prepare = connexion::pdo()->prepare($request);
        $values['id'] = $id;
        try {
            $prepare->execute($values);
            return true;
        } catch (Exception $e) {
            http_response_code(400);
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return false;
        }
    }
}
