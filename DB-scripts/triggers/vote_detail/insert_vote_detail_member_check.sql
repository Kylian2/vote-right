DELIMITER //

-- Vérifier que le membre votant une proposition est bien membre du groupe
CREATE TRIGGER insertVotedetailMemberCheck
BEFORE INSERT ON vote_detail
FOR EACH ROW
BEGIN
    DECLARE community_id INT;

    SELECT PRO_community_NB INTO community_id
    FROM Proposal
    WHERE PRO_id_NB = NEW.DET_proposal_NB;

    IF NEW.DET_user_NB NOT IN (SELECT MEM_user_NB
                               FROM Member 
                               WHERE MEM_community_NB = community_id)
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les membres du groupe peuvent voter une proposition.';
    END IF;
END //

DELIMITER ;