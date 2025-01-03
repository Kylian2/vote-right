DELIMITER //

CREATE OR REPLACE FUNCTION reaction_formatted(proposal INT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE like_list TEXT DEFAULT '[';
    DECLARE dislike_list TEXT DEFAULT '[';
    DECLARE first_like INT DEFAULT 1;
    DECLARE first_dislike INT DEFAULT 1;
    DECLARE user_id INT;
    DECLARE reaction INT;

    DECLARE reactions CURSOR FOR 
    SELECT REP_user_NB, REP_reaction_NB 
    FROM proposal_reaction
    WHERE REP_proposal_NB = proposal;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    -- Open the cursor
    OPEN reactions;

    fetchreactions: LOOP
        FETCH reactions INTO user_id, reaction;

        IF fin_cursor THEN 
            LEAVE fetchreactions;
        END IF;

        -- Add user_id to like or dislike list based on the reaction
        IF reaction = 1 OR reaction = 3 THEN
            IF first_like = 0 THEN
                SET like_list = CONCAT(like_list, ', ');
            END IF;
            SET like_list = CONCAT(like_list, user_id);
            SET first_like = 0;
        ELSEIF reaction = 2 OR reaction = 4 THEN
            IF first_dislike = 0 THEN
                SET dislike_list = CONCAT(dislike_list, ', ');
            END IF;
            SET dislike_list = CONCAT(dislike_list, user_id);
            SET first_dislike = 0;
        END IF;
    END LOOP;

    -- Close the cursor
    CLOSE reactions;

    -- Finalize the lists
    SET like_list = CONCAT(like_list, ']');
    SET dislike_list = CONCAT(dislike_list, ']');

    -- Construct the JSON result
    RETURN CONCAT(
        '{',
        '"like": ', like_list, ', ',
        '"dislike": ', dislike_list,
        '}'
    );
END //

DELIMITER ;