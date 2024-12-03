<?php 

@require_once('models/model.php');

define("ROLE_ADMIN",1);

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

}

?>