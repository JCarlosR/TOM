<?php

Route::get('/', 'GuessController@index');
Route::auth();

// Stories
Route::get('/stories', 'GuessController@getStories');
// Contact
Route::get('/contact', 'GuessController@getContact');
Route::post('/contact', 'GuessController@postContact');
// Privacy & terms
Route::get('/privacy', 'GuessController@privacy');
Route::get('/terms', 'GuessController@terms');

// Generate a login URL
Route::get('/facebook/login', 'FacebookController@login');
// Callback after authentication
Route::get('/facebook/callback', 'FacebookController@callback');

// Creators welcome
Route::get('/home', 'HomeController@index');
Route::get('/tutorial', 'Creator\TutorialController@index');
Route::get('/tutorial/disable', 'Creator\TutorialController@disable');
// Creator configuration
Route::get('/config', 'Creator\FanPageController@index');
Route::get('/config/page/{id}', 'Creator\FanPageController@show');
Route::get('/config/page/{id}/promotions', 'Creator\FanPageController@promotions');
Route::get('/config/promotion/{id}/excel', 'Creator\FanPageController@excel');

// Promotions for creators
Route::post('/promotion', 'Creator\PromotionController@store');
Route::get('/promotion/{id}/instructions', 'Creator\ConfigController@instructions');
Route::get('/config/promotion/{id}/edit', 'Creator\PromotionController@edit');
Route::post('/config/promotion/{id}/edit', 'Creator\PromotionController@update');
Route::get('/config/promotion/{id}/delete', 'Creator\PromotionController@delete');

// Promotions for participants
Route::get('/promotion/{id}', 'Participant\PromotionController@show');
Route::match(['get', 'post'], '/facebook/promotion/{id}', 'Participant\PromotionController@requestFbPermissions');
// Participate action
Route::post('/promotion/{id}/go', 'Participant\PromotionController@go');

// Post to fb group
Route::get('/admin/post', 'Creator\PostController@grantPermissions');
Route::get('/admin/posts/callback', 'Creator\PostController@test');

// Admin routes
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('/users', 'Admin\CreatorController@index');
});