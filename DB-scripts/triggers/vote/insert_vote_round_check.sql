DELIMITER //

-- Vérifier que le tour du vote ne dépasse pas le nombre maximum de tours du suffrage suivi par ce vote
CREATE OR REPLACE TRIGGER insert_vote_round_check
BEFORE INSERT ON vote
FOR EACH ROW
BEGIN
    DECLARE nb_max_rounds TINYINT;

    -- Récupérer le nombre maximum de tours autorisés pour le système de vote
    SELECT SYS_nb_rounds_NB INTO nb_max_rounds
    FROM voting_system
    WHERE SYS_id_NB = NEW.VOT_type_NB;

    IF NEW.VOT_round_NB > nb_max_rounds THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Impossible d''ajouter plus de tours à ce vote.';
    END IF;
END //

DELIMITER ;