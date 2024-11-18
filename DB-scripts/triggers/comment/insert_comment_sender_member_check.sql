DELIMITER //

-- Vérifier que l'utilisateur commentant une proposition est membre du groupe
CREATE TRIGGER insertCommentSenderMemberCheck
BEFORE INSERT ON comment
FOR EACH ROW
BEGIN
    DECLARE id_community INT;

    SELECT PRO_community_NB INTO id_community
    FROM proposal
    WHERE PRO_id_NB = NEW.COM_proposal_NB;

    IF NEW.COM_sender_NB NOT IN (
        SELECT MEM_user_NB
        FROM member 
        WHERE MEM_community_NB = id_community)
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les membres du groupe peuvent commenter une proposition.';
    END IF;
END //

DELIMITER ;