<?php 

@require_once('models/model.php');

class Reason extends Model{

    public int $RES_id_NB;
    public string $RES_label_VC;

    public static function getReasons(){
        $request = "SELECT * FROM reason";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "reason");
        $reasons = $result->fetchAll();
        return $reasons;
    }

}

?>