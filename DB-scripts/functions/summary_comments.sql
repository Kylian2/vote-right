DELIMITER //

CREATE OR REPLACE FUNCTION summary_comments(user_id INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE comment_id BIGINT;
    DECLARE comment_message VARCHAR(250);
    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);
    DECLARE user_firstname VARCHAR(50);

    DECLARE one_comment TEXT;
    DECLARE list_comments TEXT DEFAULT '[';
    DECLARE fin_cursor INT DEFAULT 0;

    DECLARE first INT DEFAULT 1; -- Indicateur pour la première entrée sans virgule initiale

    DECLARE comments CURSOR FOR
        SELECT com_id_nb, com_message_vc, pro_id_nb, pro_title_vc, usr_firstname_vc
        FROM proposal
        INNER JOIN comment ON com_proposal_nb = pro_id_nb
        INNER JOIN user ON com_sender_nb = usr_id_nb
        WHERE pro_community_nb IN (SELECT mem_community_nb FROM member WHERE mem_user_nb = user_id)
        AND pro_status_vc = 'En cours'
        AND com_creation_date >= CURDATE() - INTERVAL 7 DAY;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN comments;

    fetchcomments: LOOP
        FETCH comments INTO comment_id, comment_message, proposal_id, proposal_title, user_firstname;

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
            '"user_firstname": "', user_firstname, '"',
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
