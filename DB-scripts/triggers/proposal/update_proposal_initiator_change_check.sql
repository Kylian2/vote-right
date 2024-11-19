DELIMITER //

-- Vérifier qu'une proposition ne change pas de créateur
CREATE OR REPLACE TRIGGER update_proposal_initiator_change_check
BEFORE UPDATE ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_initiator_NB != OLD.PRO_initiator_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Vous ne pouvez pas changer le créateur de la proposition.';
    END IF;
END //

DELIMITER ;