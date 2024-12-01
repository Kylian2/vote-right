DELIMITER //

CREATE OR REPLACE FUNCTION summary_reactions_on_proposals(user_id INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);
    DECLARE proposal_nblike SMALLINT;
    DECLARE proposal_nbdislike SMALLINT;
    DECLARE proposal_nblove SMALLINT;
    DECLARE proposal_nbhate SMALLINT;

    DECLARE one_proposal TEXT;
    DECLARE list_proposal TEXT DEFAULT '[';
    
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1; -- Indicateur pour la première entrée sans virgule initiale

    DECLARE proposals CURSOR FOR
        SELECT pr.pro_id_nb, pr.pro_title_vc, nblove, nblike, nbdislike, nbhate
        FROM proposal_total_reaction pr
        INNER JOIN proposal p ON p.pro_id_nb = pr.pro_id_nb
        WHERE pro_initiator_nb = user_id AND pr.pro_community_nb IN (SELECT mem_community_nb FROM member WHERE mem_user_nb = 92);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN proposals;

    fetchproposals: LOOP
        FETCH proposals INTO proposal_id, proposal_title, proposal_nblove, proposal_nblike, proposal_nbdislike, proposal_nbhate;

        IF fin_cursor THEN 
            LEAVE fetchproposals; 
        END IF;

        -- Construction de l'objet JSON pour chaque proposition
        SET one_proposal = CONCAT(
            '{',
            '"id": "', proposal_id, '",',
            '"title": "', proposal_title, '",',
            '"nblove": "', proposal_nblove, '",',
            '"nblike": "', proposal_nblike, '",',
            '"nbdislike": "', proposal_nbdislike, '",',
            '"nbhate": "', proposal_nbhate, '"',
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
