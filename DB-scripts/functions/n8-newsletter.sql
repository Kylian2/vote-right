DELIMITER //

CREATE OR REPLACE FUNCTION newsletter(user_id INT) 
RETURNS TEXT 
NOT DETERMINISTIC 
BEGIN
    DECLARE community_id INT;
    DECLARE community_name VARCHAR(150);
    DECLARE community_emoji VARCHAR(5);
    DECLARE community_info_json TEXT;
    DECLARE current_community TEXT;
    DECLARE newsletter TEXT DEFAULT '[';
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1; -- Indicateur pour gérer la première entrée sans virgule initiale

    DECLARE most_liked_proposals TEXT DEFAULT '[]';
    DECLARE new_proposals TEXT DEFAULT '[]';
    DECLARE most_liked_comments TEXT DEFAULT '[]';
    DECLARE polemic_proposal TEXT DEFAULT '[]';
    DECLARE accepted_proposals TEXT DEFAULT '[]';
    DECLARE forthcoming_votes TEXT DEFAULT '[]';

    DECLARE communities CURSOR FOR 
        SELECT CMY_id_NB, CMY_name_VC, CMY_emoji_VC
        FROM community
        WHERE CMY_id_NB IN (SELECT MEM_community_NB FROM member WHERE MEM_user_NB = user_id);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN communities;

    -- Boucle sur chaque groupe
    makeletter: LOOP
        FETCH communities INTO community_id, community_name, community_emoji;

        IF fin_cursor THEN
            LEAVE makeletter;
        END IF;

        SET most_liked_proposals = newsletter_most_liked_proposals(community_id); 
        SET new_proposals = newsletter_new_proposals(community_id); 
        SET most_liked_comments = newsletter_most_liked_comments(community_id); 
        SET polemic_proposal = newsletter_polemic_proposal(community_id);  
        SET accepted_proposals = newsletter_accepted_proposals(community_id);
        SET forthcoming_votes = newsletter_forthcoming_votes(community_id);

        -- Création de l'objet JSON pour la communauté sous forme de texte
        SET community_info_json = CONCAT(
            '{',
                '"community": {',
                    '"id": "', community_id, '",',
                    '"name": "', community_name, '",',
                    '"emoji": "', community_emoji, '"',
                '},',
                '"new_proposals": ', new_proposals, ',',  -- Liste associée aux propositions
                '"most_liked_proposals": ', most_liked_proposals, ',',  -- Liste associée aux propositions
                '"most_liked_comments": ', most_liked_comments, ',',
                '"polemic_proposal": ', polemic_proposal, ',',
                '"accepted_proposals": ', accepted_proposals, ',',
                '"forthcoming_votes": ', forthcoming_votes,
            '}'
        );

        -- Ajouter la communauté à la newsletter, en gérant la virgule pour chaque élément
        IF first = 1 THEN
            SET newsletter = CONCAT(newsletter, community_info_json);
            SET first = 0;
        ELSE
            SET newsletter = CONCAT(newsletter, ',', community_info_json);
        END IF;
    END LOOP;

    CLOSE communities;

    -- Fermer la liste JSON
    SET newsletter = CONCAT(newsletter, ']');

    RETURN newsletter;
END //

DELIMITER ;
