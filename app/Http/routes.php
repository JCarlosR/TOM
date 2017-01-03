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

// Creators
Route::get('/home', 'HomeController@index');
Route::get('/config', 'HomeController@config');
Route::get('/config/page/{id}', 'FanPageController@index');
Route::get('/config/page/{id}/promotions', 'FanPageController@promotions');
Route::get('/config/page/{id}/excel', 'FanPageController@excel');

// Promotions for creators
Route::post('/promotion', 'Creator\PromotionController@store');
Route::get('/promotion/{id}/instructions', 'Creator\PromotionController@instructions');

// Promotions for participants
Route::get('/promotion/{id}', 'Participant\PromotionController@show');
Route::match(['get', 'post'], '/facebook/promotion/{id}', 'Participant\PromotionController@requestFbPermissions');
// Participate action
Route::post('/promotion/{id}/go', 'Participant\PromotionController@go');
