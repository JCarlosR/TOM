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
// Endpoint that is redirected to after an authentication attempt
Route::get('/facebook/callback', 'FacebookController@callback');

Route::get('/home', 'HomeController@index');
Route::get('/config', 'HomeController@config');
Route::get('/config/page/{id}', 'FanPageController@index');
Route::get('/config/page/{id}/promotions', 'FanPageController@promotions');
Route::get('/config/page/{id}/excel', 'FanPageController@excel');

// Promotions
Route::post('/promotion', 'ConfigController@store');
Route::get('/promotion/{id}/instructions', 'ConfigController@instructions');

// Show promotion to participants
Route::get('/promotion/{id}', 'PromotionController@show');
Route::match(['get', 'post'], '/facebook/promotion/{id}', 'PromotionController@requestFbPermissions');
// Participations
Route::post('/promotion/{id}/go', 'PromotionController@go');
