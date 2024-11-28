DELIMITER //

-- Vérifier que le tour du vote ne dépasse pas le nombre maximum de tours du suffrage suivi par ce vote
CREATE TRIGGER insert_vote_round_check
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
        SET MESSAGE_TEXT = CONCAT('Erreur : Impossible de créer un ',
                                   NEW.VOT_round_NB,
                                   'ème tour car le vote suit un suffrage à maximum ',
                                   nb_max_rounds,
                                   ' tour(s).');
    END IF;
END //

DELIMITER ;