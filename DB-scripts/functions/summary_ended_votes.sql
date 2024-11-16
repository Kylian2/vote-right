DELIMITER //

CREATE OR REPLACE FUNCTION summary_ended_votes(user_id INT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN 
    DECLARE proposal_id INT;
    DECLARE proposal_title VARCHAR(150);
    DECLARE vote_end DATE;
    DECLARE vote_round TINYINT;

    DECLARE one_proposal TEXT;
    DECLARE list_proposal TEXT DEFAULT '[';

    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1;

    DECLARE votes CURSOR FOR 
        SELECT pro_id_nb, pro_title_vc, vot_end_date, vot_round_nb
        FROM proposal
        INNER JOIN vote ON pro_id_nb = vot_proposal_nb
        WHERE pro_status_vc = 'En cours' AND pro_deleter_nb IS NULL
        AND pro_community_nb IN (SELECT mem_community_nb FROM member WHERE mem_user_nb = user_id)
        AND ((CURDATE() <= vot_end_date AND vot_end_date <= CURDATE() + INTERVAL 7 DAY));

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN votes;
    
    fetchvotes: LOOP
        FETCH votes INTO proposal_id, proposal_title, vote_end, vote_round;

        IF fin_cursor THEN 
            LEAVE fetchvotes;
        END IF;

        SET one_proposal = CONCAT(
            '{',
            '"proposal_id": "', proposal_id, '",',
            '"proposal_title": "', proposal_title, '",',
            '"vote_end": "', vote_end, '",',
            '"vote_round": "', vote_round, '"',
            '}'
        );

        IF first = 1 THEN
            SET list_proposal = CONCAT(list_proposal, one_proposal);
            SET first = 0;
        ELSE
            SET list_proposal = CONCAT(list_proposal, ',', one_proposal);
        END IF;

    END LOOP;

    CLOSE votes;

    SET list_proposal = CONCAT(list_proposal, ']');

    RETURN list_proposal;

END //

DELIMITER ;