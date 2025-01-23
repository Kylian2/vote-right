<?php 

@require_once('models/model.php');

class Code extends Model{

    public int $COD_code_NB;
    public string $COD_email_VC;
    public string $COD_action_VC;

    public static function generateCode(string $email, string $action){
        $code = rand(100000, 999999);
        $request = "SELECT * FROM code WHERE COD_code_NB = :code";
        $prepare = connexion::pdo()->prepare($request);
        $values["code"] = $code;
        $prepare->execute($values);
        $result = $prepare->fetch();
        $codeok = !$result;
        while(!$codeok){
            $code = rand(100000, 999999);
            $request = "SELECT * FROM code WHERE COD_code_NB = :code";
            $prepare = connexion::pdo()->prepare($request);
            $values["code"] = $code;
            $prepare->execute($values);
            $result = $prepare->fetch();
            $codeok = !$result;
        }

        $request = "INSERT INTO code(COD_code_NB, COD_email_VC, COD_action_VC) VALUES (:code, :email, :action)";
        $prepare = connexion::pdo()->prepare($request);
        $values["email"] = $email;
        $values["action"] = $action;
        $prepare->execute($values);
        return $code;
    }

}

?>