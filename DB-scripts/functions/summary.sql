/* Cette fonction renvoie en fonction des préférence de l'utilisateur passé en paramètre : 
 - La liste des nouvelles propositions de la semaines toutes communauté confondues (PROPOSAL)
 - La liste des commentaires sur les propositions postées par l'utilisateur  (REACTION)
 - La somme des réactions j'aime j'aime pas pour chaque proposition postées par l'utilisateur (REACTION)
 - La somme des réactions [idem] pour chaque commentaire posté par l'utilisateur  (REACTION)
 - La liste des votes qui ont commencés pendant la semaine (VOTE)
 - La liste des votes qui ont terminés pendant la semaine (VOTE)
*/

DELIMITER //

CREATE OR REPLACE FUNCTION summary(user_id INT)
RETURNS TEXT 
NOT DETERMINISTIC
BEGIN 

DECLARE notify_proposal BOOLEAN;
DECLARE notify_vote BOOLEAN;
DECLARE notify_reaction BOOLEAN;

DECLARE new_proposals TEXT;
DECLARE comments TEXT;
DECLARE reactions_on_proposals TEXT;
DECLARE reactions_on_comments TEXT;
DECLARE started_votes TEXT;
DECLARE ended_votes TEXT;

DECLARE summary_json TEXT;

SELECT usr_notify_proposal_bool, usr_notify_vote_bool, usr_notify_reaction_bool INTO notify_proposal, notify_vote, notify_reaction
FROM user
WHERE usr_id_nb = user_id;

IF notify_proposal THEN 
    SET new_proposals = summary_new_proposals(user_id);
END IF;

IF notify_vote THEN 
    SET started_votes = null; -- summary_started_votes(user_id);
    SET ended_votes = null; -- summary_ended_votes(user_id);
END IF;

IF notify_reaction THEN 
    SET comments = summary_comments(user_id);
    SET reactions_on_proposals = null; -- summary_reactions_on_proposals(user_id);
    SET reactions_on_comments = null; -- summary_reactions_on_comments(user_id);
END IF;

SET summary_json = CONCAT(
    '{',
        '"new_proposals": ', IF(new_proposals IS NULL, 'null', new_proposals), ',',  
        '"comments": ', IF(comments IS NULL, 'null', comments), ',', 
        '"reactions_on_proposals": ', IF(reactions_on_proposals IS NULL, 'null', reactions_on_proposals), ',',
        '"reactions_on_comments": ', IF(reactions_on_comments IS NULL, 'null', reactions_on_comments), ',',
        '"started_votes": ', IF(started_votes IS NULL, 'null', started_votes), ',',
        '"ended_votes": ', IF(ended_votes IS NULL, 'null', ended_votes),
    '}'
);

RETURN summary_json;

END //

DELIMITER ;