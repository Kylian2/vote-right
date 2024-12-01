DELIMITER //

CREATE OR REPLACE FUNCTION newsletter_accepted_proposals(community_id INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);
    DECLARE proposal_nblike SMALLINT;
    DECLARE proposal_nbdislike SMALLINT;
    DECLARE proposal_nblove SMALLINT;
    DECLARE proposal_nbhate SMALLINT;
    DECLARE proposal_nbtotal SMALLINT;

    DECLARE one_proposal TEXT;
    DECLARE list_proposal TEXT DEFAULT '[';
    
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1; -- Indicateur pour la première entrée sans virgule initiale

    DECLARE proposals CURSOR FOR
        SELECT pro_id_nb, pro_title_vc
        FROM proposal
        WHERE pro_status_vc = 'Validé'
        AND pro_creation_date >= CURDATE() - INTERVAL 7 DAY
        AND pro_deleter_nb IS NULL
        AND pro_community_nb = community_id;

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
