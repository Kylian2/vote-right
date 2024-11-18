DELIMITER //

-- Vérifier que le membre créant une proposition est bien membre de ce groupe.
CREATE TRIGGER insertProposalInitiatorMemberCheck
BEFORE INSERT ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_initiator_NB NOT IN (SELECT MEM_user_NB
                                    FROM member 
                                    WHERE MEM_community_NB = NEW.PRO_community_NB)
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les membres du groupe peuvent créer une proposition.';
    END IF;
END //

DELIMITER ;