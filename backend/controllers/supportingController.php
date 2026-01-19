<?php

@require_once('models/supporting.php');

class SupportingController
{
    /**
     * Récupère tous les justificatifs demandés pour une communauté
     * @param array $params Les paramètres de la route, où le premier élément est l'identifiant de la communauté
     * @return void Renvoie la liste des justificatifs au format JSON
     */
    public static function getAllOfCommunity(array $params)
    {
        $id = $params[0];
        $supportings = Supporting::getAllOf($id);
        http_response_code(200);
        echo json_encode($supportings);
    }

    /**
     * Crée un nouveau justificatif pour une communauté
     * @return void Renvoie un message de succès ou d'erreur au format JSON
     */
    public static function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $supporting = new Supporting();
        $supporting->SUP_community_NB = $data['community'];
        $supporting->SUP_label_VC = $data['name'];
        $supporting->SUP_description_TX = $data['description'];

        if ($supporting->insert()) {
            http_response_code(201);
            echo json_encode(['message' => 'Supporting created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error creating supporting']);
        }
    }
}
