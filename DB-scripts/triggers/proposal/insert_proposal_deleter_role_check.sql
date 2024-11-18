DELIMITER //

-- Vérifier que le membre supprimant une proposition est modérateur ou administrateur du groupe
CREATE TRIGGER insertProposalDeleterRoleCheck
BEFORE INSERT ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_deleter_NB IS NOT NULL
    AND NEW.PRO_deleter_NB NOT IN (
        SELECT MEM_user_NB
        FROM member
        INNER JOIN role ON ROL_id_NB = MEM_role_NB
        WHERE MEM_community_NB = NEW.PRO_community_NB AND (ROL_label_VC = 'Modérateur' OR ROL_label_VC = 'Administrateur')
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les modérateurs ou administrateurs peuvent supprimer une proposition.';
    END IF;
END //

DELIMITER ;