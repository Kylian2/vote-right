DELIMITER //

-- Vérifier que l'utilisateur supprimant un commentaire est modérateur ou administrateur du groupe
CREATE TRIGGER insertCommentSuppressorRoleCheck
BEFORE INSERT ON comment
FOR EACH ROW
BEGIN
    IF NEW.COM_suppressor_NB IS NOT NULL
    AND NEW.COM_suppressor_NB NOT IN (
        SELECT MEM_user_NB
        FROM Proposal
        INNER JOIN Member ON MEM_community_NB = PRO_community_NB
        INNER JOIN Role ON ROL_id_NB = MEM_role_NB
        WHERE PRO_id_NB = NEW.COM_proposal_NB 
        AND (ROL_label_VC = 'Modérateur' OR ROL_label_VC = 'Administrateur')) 
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les modérateurs ou administrateurs peuvent supprimer un commentaire.';
    END IF;
END //

DELIMITER ;