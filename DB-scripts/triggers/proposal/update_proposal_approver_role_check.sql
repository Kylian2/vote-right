DELIMITER //

-- Vérifier que le membre approuvant une proposition est décideur du groupe
CREATE TRIGGER updateProposalApproverRoleCheck
BEFORE UPDATE ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_approver_NB IS NOT NULL
    AND OLD.PRO_approver_NB IS NULL
    AND NEW.PRO_approver_NB NOT IN (
        SELECT MEM_user_NB
        FROM member
        INNER JOIN role ON ROL_id_NB = MEM_role_NB
        WHERE MEM_community_NB = NEW.PRO_community_NB AND ROL_label_VC = 'Décideur'
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les décideurs peuvent approuver une proposition.';
    END IF;
END //

DELIMITER ;