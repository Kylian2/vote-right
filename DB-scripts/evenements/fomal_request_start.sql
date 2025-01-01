DELIMITER //

CREATE EVENT formal_request_vote
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE proposal INT;
    DECLARE community INT;
    DECLARE duration INT;

    -- curseur pour parcourir la table vote
    DECLARE pro_cursor CURSOR FOR
    SELECT PRO_id_NB, p.PRO_community_NB
    FROM proposal p
    INNER JOIN formal_request ON FOR_proposal_NB = PRO_id_NB
    INNER JOIN vote ON VOT_proposal_NB = PRO_id_NB
    WHERE VOT_start_DATE > CURRENT_DATE()
    GROUP BY PRO_id_NB, PRO_title_VC
    HAVING COUNT(*) > (SELECT COUNT(*)/2 FROM member WHERE MEM_community_NB = p.PRO_community_NB);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN pro_cursor;

    read_loop: LOOP
        FETCH pro_cursor INTO proposal, community;

        IF done THEN
            LEAVE read_loop;
        END IF;

        SELECT DATEDIFF(VOT_end_DATE, VOT_start_DATE) INTO duration FROM vote WHERE VOT_proposal_NB = proposal AND VOT_round_NB = 1;

        UPDATE vote
        SET VOT_start_DATE = CURRENT_DATE(), VOT_end_DATE = DATE_ADD(CURRENT_DATE(), INTERVAL duration DAY) 
        WHERE VOT_proposal_NB = proposal AND VOT_round_NB = 1;

    END LOOP;

    CLOSE pro_cursor;
END //

DELIMITER ;



