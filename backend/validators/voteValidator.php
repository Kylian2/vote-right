<?php

class VoteValidator{

    /**
     * Valide une entrée de vote.
     *
     * Vérifie les points suivants :
     * - `system` : doit être un entier positif
     * - `discussionDuration` : doit être un entier positif
     * - `voteDuration` : doit être un entier positif
     * - `possibilities` : doit être un tableau de chaînes de caractères valides sans caractères étranges
     *
     * Fini en nettoyant les strings des caractères inopportuns pouvant causer des problèmes.
     *
     * @return true si les données sont valides.
     */
    public static function validateVoteData(array &$data){

        if(!isset($data["system"]) || !is_int($data["system"]) || $data["system"] < 0){
            throw new Error("Invalid value for system (must be a positive integer)");
        }

        if(!isset($data["discussionDuration"]) || !is_int($data["discussionDuration"]) || $data["discussionDuration"] < 0){
            throw new Error("Invalid value for discussionDuration (must be a positive integer)");
        }

        if(!isset($data["voteDuration"]) || !is_int($data["voteDuration"]) || $data["voteDuration"] < 0){
            throw new Error("Invalid value for voteDuration (must be a positive integer)");
        } 

        if(!isset($data["possibilities"]) || !is_array($data["possibilities"])){
            throw new Error("Invalid value for possibilities (must be an array of strings)");
        }

        foreach ($data["possibilities"] as $index => $possibility) {
            if (!is_string($possibility)) {
                throw new Error("Invalid value in possibilities at index $index (must be a string)");
            }
            $possibility = filter_var($possibility, FILTER_FLAG_STRIP_BACKTICK);
            $possibility = str_replace('"', '', $possibility);
            $possibility = filter_var($possibility, FILTER_FLAG_EMPTY_STRING_NULL);

        }

        return true;
    }
}

?>
