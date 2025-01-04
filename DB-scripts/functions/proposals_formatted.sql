DELIMITER //

CREATE OR REPLACE FUNCTION proposal_formatted(community INT, input_year INT)
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE json_result TEXT DEFAULT '[';
    DECLARE first_entry INT DEFAULT 1;
    DECLARE proposal_id INT;
    DECLARE title VARCHAR(255);
    DECLARE budget DECIMAL(10, 2);
    DECLARE theme INT;
    DECLARE vote_json TEXT;
    DECLARE reaction_json TEXT;

    DECLARE proposals CURSOR FOR 
    SELECT PRO_id_NB, PRO_title_VC, PRO_budget_NB, PRO_theme_NB
    FROM proposal
    INNER JOIN vote ON VOT_proposal_NB = PRO_id_NB
    INNER JOIN voting_system ON VOT_type_NB = SYS_id_NB
    WHERE VOT_round_NB = SYS_nb_rounds_NB AND VOT_valid_BOOL = TRUE AND PRO_community_NB = community AND PRO_period_YEAR = input_year AND PRO_status_VC = 'En cours';

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    -- Open the cursor
    OPEN proposals;

    fetchproposals: LOOP
        FETCH proposals INTO proposal_id, title, budget, theme;

        IF fin_cursor THEN
            LEAVE fetchproposals;
        END IF;

        -- Get vote details
        SET vote_json = vote_formatted(proposal_id);

        -- Get reaction details
        SET reaction_json = reaction_formatted(proposal_id);

        -- Append to JSON result
        IF first_entry = 0 THEN
            SET json_result = CONCAT(json_result, ', ');
        END IF;
        SET json_result = CONCAT(json_result, '{',
            '"PRO_id_NB": ', proposal_id, ', ',
            '"PRO_title_VC": "', title, '", ',
            '"PRO_budget_NB": ', budget, ', ',
            '"PRO_theme_NB": ', theme, ', ',
            '"vote": ', vote_json, ', ',
            '"reaction": ', reaction_json,
            '}');
        SET first_entry = 0;
    END LOOP;

    -- Close the cursor
    CLOSE proposals;

    -- Finalize JSON result
    SET json_result = CONCAT(json_result, ']');

    RETURN json_result;
END //

DELIMITER ;
