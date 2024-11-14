DELIMITER //

CREATE OR REPLACE FUNCTION summary_new_proposals(user_id int) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN 

    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);

    DECLARE one_proposal TEXT;
    DECLARE list_proposal TEXT DEFAULT '[';

    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1;

    DECLARE proposals CURSOR FOR 
        SELECT pro_id_nb, pro_title_vc
        FROM proposal
        WHERE pro_community_nb IN (SELECT mem_community_nb FROM member WHERE mem_user_nb = user_id)
        AND pro_status_vc = 'En cours'
        AND pro_creation_date >= CURDATE() - INTERVAL 7 DAY;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

OPEN proposals;

    fetchproposals: LOOP
        FETCH proposals INTO proposal_id, proposal_title;

        IF fin_cursor THEN 
            LEAVE fetchproposals; 
        END IF;

        -- Construction de l'objet JSON pour chaque proposition
        SET one_proposal = CONCAT(
            '{',
            '"id": "', proposal_id, '",',
            '"title": "', proposal_title, '"',
            '}'
        );

        -- Ajout de l'objet à la liste avec gestion de la virgule initiale
        IF first = 1 THEN
            SET list_proposal = CONCAT(list_proposal, one_proposal);
            SET first = 0;
        ELSE
            SET list_proposal = CONCAT(list_proposal, ',', one_proposal);
        END IF;
    END LOOP;

    CLOSE proposals;

    -- Fermeture de la liste JSON
    SET list_proposal = CONCAT(list_proposal, ']');

    RETURN list_proposal;
END //

DELIMITER ;
