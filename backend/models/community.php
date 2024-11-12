<?php 

@require_once('models/model.php');

class Community extends Model{

    public int $CMY_id_NB;
    public string $CMY_name_VC;
    public string $CMY_image_VC;
    public string $CMY_emoji_VC;
    public ?string $CMY_description_VC;
    public float $CMY_budget_NB;
    public float $CMY_fixed_fees_NB;
    public User $CMY_creator_NB;
    
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

        $communities = array();

        foreach (json_decode($result[0]) as $community) {
            $community = json_decode($community, true);
            $community['CMY_themes_TAB'] = json_decode($community['CMY_themes_TAB']);
            $communities[] = new Community($values = $community);
        }

        return $communities;
    }

}

?>