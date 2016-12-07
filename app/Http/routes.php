<?php

Route::get('/', 'GuessController@index');
Route::auth();

// Generate a login URL
Route::get('/facebook/login', 'FacebookController@login');
// Endpoint that is redirected to after an authentication attempt
Route::get('/facebook/callback', 'FacebookController@callback');

Route::get('/home', 'HomeController@index');
Route::get('/config', 'HomeController@config');
Route::get('/config/page/{id}', 'FanPageController@index');

// Promotions
Route::post('/promotion', 'PromotionController@store');
Route::get('/promotion/{id}', 'PromotionController@show');