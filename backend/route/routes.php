<?php

@require_once('core/router.php');

Router::post('/auth/register', 'AuthController@register');
Router::post('/auth/login', 'AuthController@login');
Router::get('/auth/check', 'AuthController@check');
Router::post('/auth/logout', 'AuthController@logout');

Router::get('/communities', 'CommunityController@index', true);
Router::post('/communities', 'CommunityController@store', true);
Router::get('/communities/administered', 'CommunityController@administered', true);

Router::get('/users', 'UserController@index');
Router::get('/users/me/name', 'UserController@name', true);

Router::get('/proposals/ongoing', 'ProposalController@ongoing', true);
Router::get('/proposals/finished', 'ProposalController@finished', true);

?>
