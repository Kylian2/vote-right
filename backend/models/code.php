<?php 

@require_once('models/model.php');

class Code extends Model{

    public int $COD_code_NB;
    public string $COD_email_VC;
    public string $COD_action_VC;

    public static function generateCode(string $email, string $action){
        $codeok = false;
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

    public static function checkCode(string $email, string $code, string $action) {
        $request = "SELECT * FROM code WHERE COD_email_VC = :email AND COD_code_NB = :code AND COD_action_VC = :action";
        $prepare = connexion::pdo()->prepare($request);
        $info1['email'] = $email;
        $info1['code'] = $code;
        $info1['action'] = $action;
        $prepare->execute($info1);
        $result = $prepare->fetch();
        if(!$result){
            throw new Exception('Incorrect code');
            return;
        }
        $request = 'DELETE FROM code WHERE COD_email_VC = :email AND COD_code_NB = :code';
        $prepare = connexion::pdo()->prepare($request);
        $info2['email'] = $email;
        $info2['code'] = $code;
        $prepare->execute($info2);
        return true;
    }
}

?>