DELIMITER //

-- Vérifier que le membre validant un vote pour un groupe est bien assesseur de ce groupe
CREATE TRIGGER updateVoteAssessorCheck
BEFORE UPDATE ON vote
FOR EACH ROW
BEGIN
    IF NEW.VOT_assessor_NB IS NOT NULL
    AND OLD.VOT_assessor_NB IS NULL
    AND NEW.VOT_assessor_NB NOT IN (
        SELECT MEM_user_NB
        FROM Proposal
        INNER JOIN Member ON MEM_community_NB = PRO_community_NB
        INNER JOIN Role ON ROL_id_NB = MEM_role_NB
        WHERE PRO_id_NB = NEW.VOT_proposal_NB AND ROL_label_VC = 'Assesseur'
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les assesseurs peuvent valider un vote.';
    END IF;
END //

DELIMITER ;