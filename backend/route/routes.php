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
Router::get('/communities/{id}/adopted', 'communityController@adoptedProposals', true);
Router::get('/communities/{id}/voted', 'communityController@votedProposals', true);
Router::get('/communities/{id}/members', 'communityController@members', true);
Router::post('/communities/{id}/members', 'communityController@setMembers', true);
Router::get('/communities/{id}/themes', 'communityController@themes', true);
Router::get('/communities/{id}/proposals', 'proposalController@allOfCommunity', true);
Router::get('/communities/{id}/membership', 'communityController@isMember', true);
Router::get('/communities/{id}/budget', 'communityController@budget', true);
Router::post('/communities/{id}/budget', 'communityController@setBudget', true);
Router::post('/communities/{id}/themes', 'themeController@store', true);
Router::get('/communities/managed', 'communityController@managed', true);
Router::post('/communities/{id}/exclude/{user}', 'communityController@exclude', true);
Router::get('/communities/{id}/periods', 'communityController@periods', true);

Router::get('/users', 'userController@index');
Router::get('/users/{id}', 'userController@show');
Router::get('/users/me', 'userController@me', true);
Router::get('/users/me/role/{community}', 'userController@role', true);

Router::get('/proposals/ongoing', 'proposalController@ongoing', true);
Router::get('/proposals/finished', 'proposalController@finished', true);
Router::post('/proposals', 'proposalController@store', true);
Router::patch('/proposals/{id}', 'proposalController@patch', true);
Router::get('/proposals/{id}', 'proposalController@show', true);
Router::get('/proposals/{id}/comments', 'proposalController@comments', true);
Router::get('/proposals/{id}/reactions', 'proposalController@reactions', true);
Router::post('/proposals/{id}/reactions', 'proposalController@react', true);
Router::get('/proposals/{id}/requests', 'proposalController@getRequest', true);
Router::post('/proposals/{id}/requests', 'proposalController@postRequest', true);
Router::get('/proposals/{id}/membership', 'proposalController@isMember', true);
Router::get('/proposals/{id}/votes', 'proposalController@voteInfos', true);
Router::get('/proposals/{id}/{round}/vote', 'proposalController@voteResult', true);
Router::post('/proposals/{id}/{round}/vote', 'proposalController@saveVote', true);

Router::post('/comments', 'commentController@store', true);
Router::get('/comments/{id}/reactions', 'commentController@reactions', true);
Router::post('/comments/{id}/reactions', 'commentController@react', true);
Router::post('/comments/{id}/report', 'commentController@report', true);

Router::get('/reasons', 'reasonController@index', true);

Router::get('/roles', 'roleController@index', true);

Router::post('/invitations', 'invitationController@store', true);

Router::post('/notifications/reactions/comments', 'notificationController@notifyCommentReaction', true);
Router::post('/notifications/reactions/proposals', 'notificationController@notifyReactionProposal', true);

?>
