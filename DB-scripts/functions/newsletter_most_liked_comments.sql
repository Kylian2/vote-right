DELIMITER //

CREATE OR REPLACE FUNCTION newsletter_most_liked_comments(community_id INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE comment_id BIGINT;
    DECLARE comment_message VARCHAR(250);
    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);
    DECLARE user_firstname VARCHAR(50);
    DECLARE comment_nblove SMALLINT;
    DECLARE comment_nblike SMALLINT;

    DECLARE one_comment TEXT;
    DECLARE list_comments TEXT DEFAULT '[';
    DECLARE fin_cursor INT DEFAULT 0;

    DECLARE first INT DEFAULT 1; -- Indicateur pour la première entrée sans virgule initiale

    DECLARE comments CURSOR FOR
        SELECT com_id_nb, com_message_vc, pro_id_nb, pro_title_vc, usr_firstname_vc, nblove, nblike 
        FROM comment_total_reaction
        WHERE pro_community_nb = community_id
        LIMIT 3;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN comments;

    fetchcomments: LOOP
        FETCH comments INTO comment_id, comment_message, proposal_id, proposal_title, user_firstname, comment_nblove, comment_nblike;

        IF fin_cursor THEN 
            LEAVE fetchcomments; 
        END IF;

        -- Construction de l'objet JSON pour chaque proposition
        SET one_comment = CONCAT(
            '{',
            '"comment_id": "', comment_id, '",',
            '"comment_message": "', comment_message, '",',
            '"proposal_id": "', proposal_id, '",',
            '"proposal_title": "', proposal_title, '",',
            '"user_firstname": "', user_firstname, '",',
            '"nblove": "', comment_nblove, '",',
            '"nblike": "', comment_nblike, '"',
            '}'
        );

        -- Ajout de l'objet à la liste avec gestion de la virgule initiale
        IF first = 1 THEN
            SET list_comments = CONCAT(list_comments, one_comment);
            SET first = 0;
        ELSE
            SET list_comments = CONCAT(list_comments, ',', one_comment);
        END IF;

    END LOOP;

    CLOSE comments;

    -- Fermeture de la liste JSON
    SET list_comments = CONCAT(list_comments, ']');

    RETURN list_comments;
END //

DELIMITER ;
