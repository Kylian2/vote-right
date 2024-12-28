<?php

@require_once('models/model.php');

class Role extends Model{
    public int $ROL_id_NB;
    public string $ROL_label_VC;

    public static function getAll(){
        $request = 'SELECT * FROM role;';
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "role");
        $reasons = $result->fetchAll();
        return $reasons;
    }
}

?>