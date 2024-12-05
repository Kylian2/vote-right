<?php

@require_once('core/router.php');

Router::post('/auth/register', 'authController@register');
Router::post('/auth/login', 'authController@login');
Router::get('/auth/check', 'authController@check');
Router::post('/auth/logout', 'authController@logout');

Router::get('/communities', 'communityController@index', true);
Router::get('/communities/{id}', 'communityController@show', true);
Router::post('/communities', 'communityController@store', true);
Router::get('/communities/administered', 'communityController@administered', true);

Router::get('/users', 'userController@index');
Router::get('/users/me/name', 'userController@name', true);

Router::get('/proposals/ongoing', 'proposalController@ongoing', true);
Router::get('/proposals/finished', 'proposalController@finished', true);

?>
