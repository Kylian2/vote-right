<?php

@require_once('core/router.php');

Router::get('/user/index', 'UserController@index');
Router::post('/auth/register', 'AuthController@register');
Router::post('/auth/login', 'AuthController@login');
Router::post('/auth/check', 'AuthController@check');
Router::post('/auth/logout', 'AuthController@logout');

?>