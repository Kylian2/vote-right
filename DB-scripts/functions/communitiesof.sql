DELIMITER //

-- Cette fonction renvoie la liste de communauté de l'utilisateur en paramètre, avec les thèmes de la communauté et le nombre de membre
-- Elle est utilisé pour l'écran d'accueil
CREATE OR REPLACE FUNCTION communitiesof(userid INT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE id INT;
    DECLARE name VARCHAR(150);
    DECLARE emoji VARCHAR(5);
    DECLARE image VARCHAR(50);
    DECLARE image_path VARCHAR(50);
    DECLARE color VARCHAR(50);
    DECLARE nbMembre INT;
    DECLARE themes TEXT;
    DECLARE community TEXT;
    DECLARE communities_list TEXT DEFAULT '[';
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1; -- Indicateur pour gérer la première entrée sans virgule initiale

    DECLARE communities CURSOR FOR 
        SELECT CMY_id_NB, CMY_name_VC, CMY_emoji_VC, CMY_color_VC, CMY_image_NB, FIL_path_VC as CMY_image_VC,
               (SELECT COUNT(*) FROM member WHERE MEM_community_NB = CMY_id_NB) AS nbMembre
        FROM community c
        INNER JOIN file f ON f.FIL_id_NB = c.CMY_image_NB
        WHERE CMY_id_NB IN (SELECT MEM_community_NB FROM member WHERE MEM_user_NB = userid);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN communities;

    makejson: LOOP
        FETCH communities INTO id, name, emoji, color, image, image_path, nbMembre;

        IF fin_cursor THEN
            LEAVE makejson; -- Sortir de la boucle si aucune ligne n'est trouvée
        END IF;

        -- Récupérer et formater les thèmes en chaîne JSON
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
            '"CMY_image_NB": "', image, '",',
            '"CMY_image_VC": "', image_path, '",',
            '"CMY_nb_member_NB": "', nbMembre, '",',
            '"CMY_themes_TAB": ', themes,
            '}'
        );

        -- Ajouter la communauté à la liste, en gérant la virgule pour chaque élément
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
