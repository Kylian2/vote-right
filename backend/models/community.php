<?php 

@require_once('models/model.php');

define("ROLE_ADMIN",1);
define("ROLE_DECIDER",2);

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

    public static function communitiesManagedBy(int $user){
        $request = "SELECT CMY_id_NB, CMY_name_VC, CMY_description_TXT, CMY_color_VC, CMY_emoji_VC, CMY_image_VC, 
                    (SELECT COUNT(*) FROM member WHERE MEM_community_NB = CMY_id_NB) as CMY_nb_member_NB
                    FROM community 
                    INNER JOIN member ON MEM_community_NB = CMY_id_NB
                    WHERE MEM_role_NB != 5 AND MEM_user_NB = :user";
        $prepare = connexion::pdo()->prepare($request);
        $values['user'] = $user;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "community");
        $communities = $prepare->fetchAll();
        return $communities;
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

    public function getOngoingProposals(){
        @require_once("models/proposal.php");
        $request = "SELECT p.PRO_id_NB, p.PRO_title_VC, THM_name_VC as PRO_theme_VC, CMY_color_VC as PRO_color_VC, PRO_budget_NB, PRO_period_YEAR,
                     nblove AS PRO_love_NB, nblike AS PRO_like_NB, nbdislike AS PRO_dislike_NB, nbhate AS PRO_hate_NB
                    FROM proposal p
                    INNER JOIN proposal_total_reaction pr ON p.PRO_id_NB = pr.PRO_id_NB
                    INNER JOIN theme ON p.PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_NB
                    INNER JOIN community ON p.PRO_community_NB = CMY_id_NB
                    WHERE p.PRO_status_VC = 'En cours' AND p.PRO_community_NB = :community";

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

    public function getAdoptedProposals(int $period = null){
        @require_once("models/proposal.php");

        $request = "SELECT p.PRO_id_NB, p.PRO_title_VC, THM_name_VC as PRO_theme_VC, CMY_color_VC as PRO_color_VC,
                            PRO_budget_NB, nblove as PRO_love_NB, nblike as PRO_like_NB, nbdislike as PRO_dislike_NB, nbhate as PRO_hate_NB
                    FROM proposal p
                    INNER JOIN theme ON p.PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_NB
                    INNER JOIN community ON p.PRO_community_NB = CMY_id_NB
                    INNER JOIN proposal_total_reaction pr ON pr.PRO_id_NB = p.PRO_id_NB
                    WHERE p.PRO_status_VC = 'Validée' AND p.PRO_community_NB = :community";
        
        if($period){
            $request = "SELECT p.PRO_id_NB, p.PRO_title_VC, THM_name_VC as PRO_theme_VC, CMY_color_VC as PRO_color_VC,
                            PRO_budget_NB, nblove as PRO_love_NB, nblike as PRO_like_NB, nbdislike as PRO_dislike_NB, nbhate as PRO_hate_NB
                        FROM proposal p
                        INNER JOIN theme ON p.PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_NB
                        INNER JOIN community ON p.PRO_community_NB = CMY_id_NB
                        INNER JOIN proposal_total_reaction pr ON pr.PRO_id_NB = p.PRO_id_NB
                        WHERE p.PRO_status_VC = 'Validée' AND p.PRO_community_NB = :community AND p.PRO_period_YEAR = :period";
                        $values["period"] = $period;
        }

        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposals = $prepare->fetchAll();
        return $proposals;
    }

    public function getVotedProposals(){
        @require_once("models/proposal.php");
        $request = "SELECT p.PRO_id_NB, p.PRO_title_VC, THM_name_VC as PRO_theme_VC, CMY_color_VC as PRO_color_VC, PRO_budget_NB, nblove as PRO_love_NB, nblike as PRO_like_NB, nbdislike as PRO_dislike_NB, nbhate as PRO_hate_NB
                    FROM proposal p
                    INNER JOIN vote ON VOT_proposal_NB = PRO_id_NB
                    INNER JOIN voting_system ON SYS_id_NB = VOT_type_NB
                    INNER JOIN proposal_total_reaction pr ON pr.PRO_id_NB = p.PRO_id_NB
                    INNER JOIN theme ON THM_id_NB = p.PRO_theme_NB AND THM_community_NB = p.PRO_community_NB
                    INNER JOIN community ON CMY_id_NB = p.PRO_community_NB
                    WHERE VOT_round_NB = SYS_nb_rounds_NB AND p.PRO_community_NB = :community AND VOT_end_DATE < CURRENT_DATE()
                    AND p.PRO_status_VC = 'En cours'";

        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposals = $prepare->fetchAll();
        return $proposals;
    }

    public function getMembers(){
        @require_once("models/user.php");
        $request = "SELECT USR_id_NB, USR_firstname_VC, USR_lastname_VC, ROL_label_VC, MEM_role_NB FROM members_role WHERE MEM_community_NB = :community";
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

    public function setMembers(array $members){
        @require_once("models/user.php");
        $request = 'UPDATE member SET MEM_role_NB = :role WHERE MEM_user_NB = :user AND MEM_community_NB = :community';
        $prepare = connexion::pdo()->prepare($request);
        $values['community'] = $this->get('CMY_id_NB');

        foreach($members as $user => $role){
            if(!is_numeric($user) || !is_numeric($role)){
                throw new Exception('Invalid value');
                return;
            }
            $values['user'] = $user;
            $values['role'] = $role;
            try{
                $prepare->execute($values);
            }catch(PDOException $e){
                if($e->errorInfo[2] === "Erreur : Veuillez nommer au moins un administrateur avant de vous rétrograder."){
                    throw new Exception('Missing Administrator');
                    return;
                }
                throw new Exception($e->errorInfo[2]);
                return;
            }
        }
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

    public function getBudget($period){
        $request = "SELECT THM_id_NB, THM_name_VC, BUT_amount_NB, BUT_used_budget_NB 
                    FROM used_budget ub
                    WHERE CMY_id_NB = :community AND BUT_period_YEAR = :period";
        $prepare = connexion::pdo()->prepare($request);
        $values['community'] = $this->get('CMY_id_NB');
        $values['period'] = $period;
        $prepare->execute($values);
        $budgetThemes = $prepare->fetchAll();

        $usedBudget = 0;
        for($i = 0; $i < count($budgetThemes); $i++){
            unset($budgetThemes[$i][0]);
            unset($budgetThemes[$i][1]);
            unset($budgetThemes[$i][2]);
            unset($budgetThemes[$i][3]);
            $usedBudget += $budgetThemes[$i]['BUT_used_budget_NB'];
        }

        $request = "SELECT BUC_amount_NB, BUC_fixed_fees_NB FROM community_budget WHERE BUC_community_NB = :community AND BUC_period_YEAR = :period";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $communityBudget = $prepare->fetch();

        $budget = array(
           "CMY_budget_NB" => $communityBudget['BUC_amount_NB'],
           "CMY_used_budget_NB" => $usedBudget,
           "CMY_fixed_fees_NB" => $communityBudget['BUC_fixed_fees_NB'],
           "CMY_budget_theme_NB" => $budgetThemes
        );

        return $budget;
    }

    public function setBudget(array $budgets, int $period){
        $values['community'] = $this->get('CMY_id_NB');
        $values['period'] = $period;
        foreach($budgets as $theme => $amount){
            if($theme == -1){
                $request = 'UPDATE community_budget SET BUC_fixed_fees_NB = :amount WHERE BUC_community_NB = :community AND BUC_period_YEAR = :period';
                $prepare = connexion::pdo()->prepare($request);
                $values['amount'] = $amount;
                $prepare->execute($values);
                continue;
            }
            if($theme == 0){
                $request = 'UPDATE community_budget SET BUC_amount_NB = :amount WHERE BUC_community_NB = :community AND BUC_period_YEAR = :period';
                $prepare = connexion::pdo()->prepare($request);
                $values['amount'] = $amount;
                $prepare->execute($values);
                continue;
            }
            $request = 'UPDATE theme_budget SET BUT_amount_NB = :amount WHERE BUT_theme_NB = :theme AND BUT_community_NB = :community AND BUT_period_YEAR = :period';
            $prepare = connexion::pdo()->prepare($request);
            $values['amount'] = $amount;
            $values['theme'] = $theme;
            $prepare->execute($values);
        }
    }

    public function exclude(int $member){
        $request = 'DELETE FROM member WHERE MEM_user_NB = :user AND MEM_community_NB = :community';
        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->CMY_id_NB;    
        $values["user"] = $member;

        try{
            $prepare->execute($values);
        }catch(PDOException $e){
            if($e->errorInfo[2] === "Erreur : Veuillez nommer au moins un administrateur avant de quitter le groupe."){
                throw new Exception('Missing Administrator');
                return;
            }
            throw new Exception($e->errorInfo[2]);
            return;
        }
    }

    public function getPeriods(){
        $request = "SELECT DISTINCT BUC_period_YEAR FROM community_budget WHERE BUC_community_NB = :community";
        $prepare = connexion::pdo()->prepare($request);
        $values["community"] = $this->get('CMY_id_NB');
        $prepare->execute($values);
        $result = $prepare->fetchAll();

        //Formattage du resultat
        $periods = array();
        foreach($result as $period){
            $periods[] = $period[0];
        }
        
        return array_reverse($periods);
    }

}

?>