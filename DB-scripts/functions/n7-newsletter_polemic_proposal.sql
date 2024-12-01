DELIMITER //

CREATE OR REPLACE FUNCTION newsletter_polemic_proposal(community_id INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);
    DECLARE proposal_nblike SMALLINT;
    DECLARE proposal_nbdislike SMALLINT;
    DECLARE proposal_nbcomment SMALLINT;

    DECLARE gap SMALLINT; -- Pour stocket l'écart entre les "j'aime" et les "j'aime pas"

    DECLARE ratio_min DECIMAL(12, 4);
    DECLARE current_ratio DECIMAL(12, 4);

    DECLARE polemical_proposal TEXT DEFAULT '{}';
    
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1;

    DECLARE proposals CURSOR FOR
        SELECT pro_id_nb, pro_title_vc,
        MAX(nblove) + MAX(nblike) as 'totallike', 
        MAX(nbhate) + MAX(nbdislike) as 'totaldislike',
        COUNT(com_id_nb) as 'nbcomment'
        FROM proposal_total_reaction
        LEFT JOIN comment ON pro_id_nb = com_proposal_nb
        WHERE pro_community_nb = community_id 
        AND pro_status_vc = 'En cours'
        GROUP BY pro_id_nb, pro_title_vc, pro_community_nb;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;
    
    OPEN proposals;

    fetchproposals: LOOP
        FETCH proposals INTO proposal_id, proposal_title, proposal_nblike, proposal_nbdislike, proposal_nbcomment;

        IF fin_cursor THEN 
            LEAVE fetchproposals; 
        END IF;

        IF first = 1 THEN
            -- initialisation du ratio
            SET ratio_min = ((ABS(proposal_nblike - proposal_nbdislike)) / (proposal_nbcomment+1)) + 10;
            SET first = 0;
        END IF;

        SET current_ratio = (ABS(proposal_nblike - proposal_nbdislike)) / (proposal_nbcomment+1);
    
        IF current_ratio < ratio_min THEN
            SET ratio_min = current_ratio;
            SET polemical_proposal = CONCAT(
            '{',
            '"id": "', proposal_id, '",',
            '"title": "', proposal_title, '",',
            '"nblike": "', proposal_nblike, '",',
            '"nbdislike": "', proposal_nbdislike, '",',
            '"nbcomment": "', proposal_nbcomment, '"',
            '}'
            );
        END IF;

    END LOOP;

    CLOSE proposals;

    RETURN polemical_proposal;

END //

DELIMITER ;