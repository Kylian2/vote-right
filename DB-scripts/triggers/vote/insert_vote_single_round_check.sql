DELIMITER //

-- Vérifier qu'un vote pour une proposition à un tour ne puisse pas avoir un second tour
CREATE OR REPLACE TRIGGER insert_vote_single_round_check
BEFORE INSERT ON vote
FOR EACH ROW
BEGIN
    DECLARE twoRoundSystemId TINYINT;

    -- Récupère l'identifiant du système de vote "deux tours"
    SELECT SYS_id_NB INTO twoRoundSystemId 
    FROM voting_systeme
    WHERE SYS_label_VC LIKE '%deux tours%';

    -- Vérifie si le tour est le second tour et si le vote suit un suffrage à un tour
    IF NEW.VOT_round_NB = 2 AND NEW.VOT_type_NB != twoRoundSystemId THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Impossible de créer un deuxième tour pour un suffrage à un tour.';
    END IF;
END //

DELIMITER ;