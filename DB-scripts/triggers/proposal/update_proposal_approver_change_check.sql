DELIMITER //

-- Vérifier qu'une proposition allant être validée ne le soit pas déjà
CREATE OR REPLACE TRIGGER update_proposal_approver_change_check
BEFORE UPDATE ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_approver_NB IS NOT NULL
    AND OLD.PRO_approver_NB IS NOT NULL
    AND NEW.PRO_approver_NB != OLD.PRO_approver_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Cette proposition a déjà été validée. Vous ne pouvez pas la valider à nouveau.';
    END IF;
END //

DELIMITER ;