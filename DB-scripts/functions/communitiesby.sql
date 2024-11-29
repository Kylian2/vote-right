DELIMITER //

-- Cette fonction renvoie la liste des communauté créées l'utilisateur en paramètre, avec les thèmes de la communauté et le nombre de membre
CREATE OR REPLACE FUNCTION communitiesby(userid INT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE id INT;
    DECLARE name VARCHAR(150);
    DECLARE emoji VARCHAR(5);
    DECLARE image VARCHAR(50);
    DECLARE color VARCHAR(50);
    DECLARE nbMembre INT;
    DECLARE themes TEXT;
    DECLARE community TEXT;
    DECLARE communities_list TEXT DEFAULT '[';
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1; 
    DECLARE communities CURSOR FOR 
        SELECT CMY_id_NB, CMY_name_VC, CMY_emoji_VC, CMY_color_VC, CMY_image_VC, 
               (SELECT COUNT(*) FROM member WHERE MEM_community_NB = CMY_id_NB) AS nbMembre
        FROM community
        WHERE CMY_id_NB IN (SELECT MEM_community_NB FROM member WHERE MEM_user_NB = userid AND mem_role_nb = 1);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN communities;

    makejson: LOOP
        FETCH communities INTO id, name, emoji, color, image, nbMembre;

        IF fin_cursor THEN
            LEAVE makejson;
        END IF;

        SET themes = CONCAT('["', (SELECT GROUP_CONCAT(THM_name_VC SEPARATOR '","') 
                                   FROM theme 
                                   WHERE THM_community_NB = id), '"]');

        IF themes IS NULL THEN
            SET themes = '[]';
        END IF;

        SET community = CONCAT(
            '{',
            '"CMY_id_NB": "', id, '",',
            '"CMY_name_VC": "', name, '",',
            '"CMY_emoji_VC": "', emoji, '",',
            '"CMY_color_VC": "', color, '",',
            '"CMY_image_VC": "', image, '",',
            '"CMY_nb_member_NB": "', nbMembre, '",',
            '"CMY_themes_TAB": ', themes,
            '}'
        );

        IF first = 1 THEN
            SET communities_list = CONCAT(communities_list, community);
            SET first = 0;
        ELSE
            SET communities_list = CONCAT(communities_list, ',', community);
        END IF;

    END LOOP;

    CLOSE communities;

    SET communities_list = CONCAT(communities_list, ']');

    RETURN communities_list;
END //

DELIMITER ;
