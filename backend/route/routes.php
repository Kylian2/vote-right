<?php

@require_once('core/router.php');

Router::get('/user/index', 'UserController@index');
Router::post('/auth/register', 'AuthController@register');
Router::post('/auth/login', 'AuthController@login');

?>