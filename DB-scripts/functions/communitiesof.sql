DELIMITER //

CREATE FUNCTION communitiesof(userid INT) 
RETURNS JSON
NOT DETERMINISTIC
BEGIN
    DECLARE id INT;
    DECLARE name VARCHAR(150);
    DECLARE emoji VARCHAR(5);
    DECLARE image VARCHAR(50);
    DECLARE nbMembre INT;
    DECLARE community JSON;
    DECLARE themes JSON;
    DECLARE communitiesTable JSON DEFAULT JSON_ARRAY();
    DECLARE fin_cursor INT DEFAULT 0;

    DECLARE communities CURSOR FOR 
        SELECT CMY_id_NB, CMY_name_VC, CMY_emoji_VC, CMY_image_VC, 
               (SELECT COUNT(*) FROM member WHERE MEM_community_NB = CMY_id_NB) AS nbMembre
        FROM community
        WHERE CMY_id_NB IN (SELECT MEM_community_NB FROM member WHERE MEM_user_NB = userid);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN communities;

    makejson: LOOP
        FETCH communities INTO id, name, emoji, image, nbMembre;

        IF fin_cursor THEN
            LEAVE makejson; -- Sortir de la boucle si aucune ligne n'est trouvée
        END IF;

		SET themes = CONCAT('["', (SELECT GROUP_CONCAT(THM_name_VC SEPARATOR '","') 
                                FROM theme 
                                WHERE THM_community_NB = id), '"]');
                                
        SET community = JSON_OBJECT(
            "CMY_id_NB", id,
            'CMY_name_VC', name,
            'CMY_emoji_VC', emoji,
            'CMY_image_VC', image,
            'CMY_nb_member_NB', nbMembre,
            'CMY_themes_TAB', themes
        );

        SET communitiesTable = JSON_ARRAY_APPEND(communitiesTable, '$', community);
    END LOOP;

    CLOSE communities;

    RETURN communitiesTable;
END //

DELIMITER ;