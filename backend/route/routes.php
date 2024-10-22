<?php

@require_once('core/router.php');

Router::get('/user/index', 'UserController@index');
Router::post('/user/store', 'UserController@store');

?>