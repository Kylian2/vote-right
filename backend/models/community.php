<?php 

@require_once('models/model.php');

define("ROLE_ADMIN",1);
define('ROLE_MEMBER', 5);

class Community extends Model{
    
    public int $CMY_id_NB;
    public string $CMY_name_VC;
    public string $CMY_image_VC;
    public string $CMY_emoji_VC;
    public string $CMY_color_VC;
    public ?string $CMY_description_TXT;
    public float $CMY_budget_NB;
    public float $CMY_fixed_fees_NB;
    public int $CMY_creator_NB;
    
    public array $CMY_themes_TAB;
    public int $CMY_nb_member_NB;

    public static function getById(int $id){
        $request = 'SELECT * FROM community WHERE CMY_id_NB = :id';
        $prepare = connexion::pdo()->prepare($request);
        $values['id'] = $id;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "community");
        $community = $prepare->fetch();
        
        return $community;
    }

    public static function getAll() {
        $request = "SELECT * FROM community;";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "community");
        $communities = $result->fetchAll();
        return $communities;
    }

    public static function communitiesOf(int $id){
        $request = 'SELECT communitiesof(:user);';
        $prepare = connexion::pdo()->prepare($request);
        $values['user'] = $id;
        $prepare->execute($values);
        $result = $prepare->fetch();

        return json_decode($result[0]);
    }

    public static function communitiesBy(int $id){
        $request = 'SELECT communitiesby(:user);';
        $prepare = connexion::pdo()->prepare($request);
        $values['user'] = $id;
        $prepare->execute($values);
        $result = $prepare->fetch();

        return json_decode($result[0]);
    }

    public function insert(){
        $request = 'INSERT INTO community (CMY_name_VC, CMY_image_VC, CMY_emoji_VC, CMY_color_VC, CMY_description_TXT, CMY_creator_NB)
                    VALUES (:name, :image, :emoji, :color, :description, :creator);';
        $prepare = connexion::pdo()->prepare($request);
        $values = array(
            "name" => $this->CMY_name_VC,
            "image" => $this->CMY_image_VC,
            "emoji" => $this->CMY_emoji_VC,
            "color" => $this->CMY_color_VC,
            "description" => $this->CMY_description_TXT,
            "creator" => $this->CMY_creator_NB,
        );
        $prepare->execute($values);
        $communityId = connexion::pdo()->lastInsertId();
        $this->set('CMY_id_NB', $communityId);

        $request = 'INSERT INTO member(MEM_community_NB, MEM_user_NB, MEM_role_NB) VALUES (:community, :user, :role)';
        $prepare = connexion::pdo()->prepare($request);
        $member = array(
            "community" => $this->CMY_id_NB,
            "user" => $this->CMY_creator_NB,
            "role" => ROLE_ADMIN,
        );
        $prepare->execute($member);

        return true;
    }

    public static function addMember(array $params){
        $request = 'INSERT INTO member(MEM_community_NB, MEM_user_NB, MEM_role_NB) 
                    VALUES (:community, :user, :role)';
        $prepare = connexion::pdo()->prepare($request);
        $member = array(
            "community" => $params["CMY_id_NB"],
            "user" => $params["CMY_member_NB"],
            "role" => ROLE_MEMBER,
        );
        $prepare->execute($member);
    }

    public function getOngoingProposals(){
        @require_once("models/proposal.php");
        $request = "SELECT PRO_id_NB, PRO_title_VC, THM_name_VC as PRO_theme_VC, CMY_color_VC as PRO_color_VC
                    FROM proposal
                    INNER JOIN theme ON PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_NB
                    INNER JOIN community ON PRO_community_NB = CMY_id_NB
                    WHERE PRO_status_VC = 'En cours' AND PRO_community_NB = :community";

        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposals = $prepare->fetchAll();
        return $proposals;
    }

    public function getFinishedProposals(){
        @require_once("models/proposal.php");
        $request = "SELECT PRO_id_NB, PRO_title_VC, THM_name_VC as PRO_theme_VC, CMY_color_VC as PRO_color_VC
                    FROM proposal
                    INNER JOIN theme ON PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_NB
                    INNER JOIN community ON PRO_community_NB = CMY_id_NB
                    WHERE PRO_status_VC != 'En cours' AND PRO_community_NB = :community
                    LIMIT 6";

        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposals = $prepare->fetchAll();
        return $proposals;
    }

    public function getMembers(){
        @require_once("models/user.php");
        $request = "SELECT USR_firstname_VC, USR_lastname_VC, ROL_label_VC, MEM_role_NB FROM members_role WHERE MEM_community_NB = :community";
        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $prepare->execute($values);
        $result = $prepare->fetchAll();
        for($i = 0; $i < count($result); $i++){
            unset($result[$i][0]);
            unset($result[$i][1]);
            unset($result[$i][2]);
            unset($result[$i][3]);
        }
        return $result;
    }

    public function getThemes(){
        @require_once("models/theme.php");
        $request = "SELECT * FROM theme WHERE THM_community_NB = :community";
        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "theme");
        $themes = $prepare->fetchAll();
        return $themes;
    }

    public static function isMember(int $community, int $user){
        $request = "SELECT COUNT(*) FROM member WHERE MEM_user_NB = :user AND MEM_community_NB = :community";
        $prepare = connexion::pdo()->prepare($request);
        $values["user"] = $user;
        $values["community"] = $community;
        $prepare->execute($values);
        $result = $prepare->fetch();
        return boolval($result[0]);
    }

}

?>