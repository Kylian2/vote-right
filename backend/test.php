<?php
    //header('Content-Type: application/json'); // pour préciser que le contenu renvoyé est du json

@require_once("config/connexion.php");
@require_once("models/community.php");

connexion::connect();

//$result = Community::communitiesOf(92);
//$communities = Community::getAll(92);

//echo "<pre>";
//print_r($result);
//echo json_encode($communities);
//echo "</pre>";

$request = 'SELECT newsletter(:user);';
$prepare = connexion::pdo()->prepare($request);
$values['user'] = 92;
$prepare->execute($values);
$result = $prepare->fetch();

// Décoder le JSON
$decoded_result = json_decode($result[0], true);

echo "<pre>";
print_r($decoded_result);
echo "</pre>";

?>