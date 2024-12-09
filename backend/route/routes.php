<?php

@require_once('core/router.php');

Router::post('/auth/register', 'authController@register');
Router::post('/auth/login', 'authController@login');
Router::get('/auth/check', 'authController@check');
Router::post('/auth/logout', 'authController@logout'); //à implémenter + faire la doc

Router::get('/communities', 'communityController@index', true);
Router::get('/communities/{id}', 'communityController@show', true);
Router::post('/communities', 'communityController@store', true);
Router::get('/communities/administered', 'communityController@administered', true);
Router::get('/communities/{id}/ongoing', 'communityController@ongoingProposals', true);
Router::get('/communities/{id}/finished', 'communityController@finishedProposals', true);
Router::get('/communities/{id}/members', 'communityController@members', true);
Router::get('/communities/{id}/themes', 'communityController@themes', true);

Router::get('/users', 'userController@index');
Router::get('/users/me/name', 'userController@name', true);
Router::get('/users/me/role/{community}', 'userController@role', true);

Router::get('/proposals/ongoing', 'proposalController@ongoing', true);
Router::get('/proposals/finished', 'proposalController@finished', true);
Router::post('/proposals', 'proposalController@store', true);

?>
