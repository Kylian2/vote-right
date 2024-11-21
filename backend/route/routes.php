<?php

@require_once('core/router.php');

Router::get('/user/index', 'UserController@index');
Router::post('/auth/register', 'AuthController@register');
Router::post('/auth/login', 'AuthController@login');
Router::get('/auth/check', 'AuthController@check');
Router::post('/auth/logout', 'AuthController@logout');

Router::get('/community/index', 'CommunityController@index');
Router::protect('/community/index');
Router::get('/community/administered', 'CommunityController@administered');
Router::protect('/community/administered');

Router::protect('/user/index');
Router::get('/user/name', 'UserController@name');
Router::protect('/user/name');

Router::get('/proposal/ongoing', 'ProposalController@ongoing');
Router::protect('/proposal/ongoing');
Router::get('/proposal/finished', 'ProposalController@finished');
Router::protect('/proposal/finished');

?>