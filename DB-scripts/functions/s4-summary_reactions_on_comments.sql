DELIMITER //

CREATE OR REPLACE FUNCTION summary_reactions_on_comments(user_id INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE comment_id BIGINT;
    DECLARE comment_message VARCHAR(250);
    DECLARE proposal_id INT;
    DECLARE comment_nblove SMALLINT;
    DECLARE comment_nblike SMALLINT;
    DECLARE comment_nbdislike SMALLINT;
    DECLARE comment_nbhate SMALLINT;

    DECLARE one_comment TEXT;
    DECLARE list_comments TEXT DEFAULT '[';
    DECLARE fin_cursor INT DEFAULT 0;

    DECLARE first INT DEFAULT 1; -- Indicateur pour la première entrée sans virgule initiale

    DECLARE comments CURSOR FOR
        SELECT com_id_nb, com_message_vc, pro_id_nb, nblove, nblike, nbdislike, nbhate
        FROM comment_total_reaction
        WHERE usr_id_nb = user_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN comments;

    fetchcomments: LOOP
        FETCH comments INTO comment_id, comment_message, proposal_id, comment_nblove, comment_nblike, comment_nbdislike, comment_nbhate;

        IF fin_cursor THEN 
            LEAVE fetchcomments; 
        END IF;

        -- Construction de l'objet JSON pour chaque proposition
        SET one_comment = CONCAT(
            '{',
            '"comment_id": "', comment_id, '",',
            '"comment_message": "', comment_message, '",',
            '"proposal_id": "', proposal_id, '",',
            '"nblove": "', comment_nblove, '",',
            '"nblike": "', comment_nblike, '",',
            '"nbdislike": "', comment_nbdislike, '",',
            '"nbhate": "', comment_nbhate, '"',
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
