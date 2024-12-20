DELIMITER //

CREATE OR REPLACE FUNCTION get_possibilities(proposal INT, round TINYINT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE possibility VARCHAR(100);
    DECLARE id INT;
    DECLARE list_pos TEXT DEFAULT '[';
    DECLARE one_pos TEXT;

    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1;

    DECLARE pos CURSOR FOR 
    SELECT POS_id_NB, POS_label_VC
    FROM possibility
    WHERE POS_proposal_NB = proposal AND POS_round_NB = round
    ORDER BY POS_id_NB;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN pos;

    fetchpos: LOOP
        FETCH pos INTO id, possibility;

        IF fin_cursor THEN 
            LEAVE fetchpos;
        END IF;

        SET one_pos = CONCAT(
            '["',possibility,'",',id,']'
        );

        IF first = 1 THEN
            SET list_pos = CONCAT(list_pos, one_pos);
            SET first = 0;
        ELSE
            SET list_pos = CONCAT(list_pos, ',', one_pos);
        END IF;
    END LOOP;

    CLOSE pos;

    SET list_pos = CONCAT(list_pos, ']');

    RETURN list_pos;

END //

DELIMITER ;