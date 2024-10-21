<?php

@require_once('core/router.php');

Router::get('/utilisateur/index', 'controleurUtilisateur@index');
Router::post('/utilisateur/store', 'controleurUtilisateur@store');

?>