DELIMITER //

CREATE EVENT launch_round
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE proposal INT;
    DECLARE round INT;
    DECLARE valid BOOL;
    DECLARE end DATETIME;
    DECLARE start DATETIME;
    DECLARE type INT;
    DECLARE majority BOOLEAN;
    DECLARE duration INT;
    DECLARE discussion_duration INT;
    DECLARE new_start_date DATETIME;
    DECLARE new_end_date DATETIME;

    -- curseur pour parcourir la table vote
    DECLARE vote_cursor CURSOR FOR
    SELECT VOT_proposal_NB, VOT_round_NB, VOT_end_DATE, VOT_start_DATE, VOT_type_NB
    FROM vote v
    INNER JOIN voting_system ON SYS_id_NB = VOT_type_NB
    WHERE VOT_end_DATE < CURRENT_DATE() AND VOT_valid_BOOL = TRUE AND SYS_nb_rounds_NB != VOT_round_NB 
    AND VOT_round_NB = (SELECT MAX(VOT_round_NB) FROM vote v2 WHERE v.VOT_proposal_NB = v2.VOT_proposal_NB);

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN vote_cursor;

    read_loop: LOOP
        FETCH vote_cursor INTO proposal, round, end, start, type;

        IF done THEN
            LEAVE read_loop;
        END IF;

        SELECT COUNT(*) > 0 INTO majority
        FROM (
            SELECT DET_choice_NB, DET_round_NB, COUNT(*) AS nb_votes
            FROM vote_detail
            WHERE DET_proposal_NB = 49 AND DET_round_NB = 1
            GROUP BY DET_choice_NB, DET_round_NB
            HAVING COUNT(*) > (
                SELECT COUNT(*) / 2 
                FROM vote_detail 
                WHERE DET_proposal_NB = 49 AND DET_round_NB = 1
            )
        ) AS majority;

        IF majority = 0 THEN
            SELECT PRO_discussion_duration_NB INTO discussion_duration
            FROM proposal WHERE PRO_id_NB = proposal;

            SELECT DATEDIFF(end, start) INTO duration;

            SET new_start_date = DATE_ADD(end, INTERVAL discussion_duration DAY);
            SET new_end_date = DATE_ADD(new_start_date, INTERVAL duration DAY);

            INSERT INTO vote (VOT_proposal_NB, VOT_round_NB, VOT_start_DATE, VOT_end_DATE, VOT_type_NB) 
            VALUES (proposal, round + 1, new_start_date, new_end_date, type);

            INSERT INTO possibility (POS_label_VC, POS_proposal_NB, POS_round_NB)
            SELECT POS_label_VC, POS_proposal_NB, POS_round_NB+1
            FROM possibility
            INNER JOIN vote_detail ON DET_choice_NB = POS_id_NB
            WHERE POS_proposal_NB = proposal AND POS_round_NB = round
            GROUP BY POS_label_VC, POS_proposal_NB, POS_round_NB
            ORDER BY COUNT(*) DESC
            LIMIT 2;
        END IF;

    END LOOP;

    CLOSE vote_cursor;
END //

DELIMITER ;
