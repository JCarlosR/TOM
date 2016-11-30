<?php

Route::get('/', function () {
    return view('welcome');
});

// Generate a login URL
Route::get('/facebook/login', 'FacebookController@login');

// Endpoint that is redirected to after an authentication attempt
Route::get('/facebook/callback', 'FacebookController@callback');
