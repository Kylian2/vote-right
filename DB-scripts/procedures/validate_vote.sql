DELIMITER //

-- Pour un vote et un utilisateur donné :
--      - met TRUE dans VOT_valid_BOOL
--      - met dans VOT_assessor_NB l’id de l'utilisateur qui a appelé la fonction
CREATE OR REPLACE PROCEDURE validate_vote (IN idProposal INT, IN idRound TINYINT, IN idUser INT)
BEGIN
    UPDATE vote
    SET VOT_assessor_NB = idUser, VOT_valid_BOOL = TRUE
    WHERE VOT_proposal_NB = idProposal AND VOT_round_NB = idRound;
END//

DELIMITER ;