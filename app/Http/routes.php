<?php

Route::get('/', 'GuessController@index');
Route::get('/invited-by/{slug}/{id}', 'GuessController@invitedBy');
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
// Clients, customers, leads
Route::get('/clientes', 'Creator\LeadController@index');
// Tutorials
Route::get('/tutorial', 'Creator\TutorialController@index');
Route::get('/tutorial/disable', 'Creator\TutorialController@disable');
Route::get('/subscribe', 'Creator\SubscriptionController@index');
// Affiliate system for creators
Route::get('/referrals/how-to', 'Creator\ReferralController@howTo');
Route::get('/referrals', 'Creator\ReferralController@myList');
Route::get('/referral/{id}/fan-pages', 'Creator\ReferralController@fanPages');
Route::get('/fan-page/{id}/promotions', 'Creator\ReferralController@promotions');
Route::get('/referrals/excel', 'Creator\ReferralController@excel');
// Earnings based on the affiliate system
Route::get('/earnings', 'Creator\EarningController@index');
Route::post('/earnings/paypal', 'Creator\EarningController@postPaypalAccount');
// Creator configuration
Route::get('/config', 'Creator\FanPageController@index');
Route::get('/config/page/{id}', 'Creator\FanPageController@show');
Route::get('/config/page/{id}/promotions', 'Creator\FanPageController@promotions');
Route::get('/config/promotion/{id}/excel', 'Creator\FanPageController@excel');

// Promotions list
Route::get('/promotions', 'Guess\PromotionController@index');
Route::get('/promotions/search', 'Guess\PromotionController@search');

// Promotions for creators
Route::post('/promotion', 'Creator\PromotionController@store');
Route::get('/promotion/{id}/instructions', 'Creator\ConfigController@instructions');
Route::get('/config/promotion/{id}/edit', 'Creator\PromotionController@edit');
Route::post('/config/promotion/{id}/edit', 'Creator\PromotionController@update');
Route::get('/config/promotion/{id}/delete', 'Creator\PromotionController@delete');

// Promotions for participants
Route::get('/promotion/{id}', 'Participant\PromotionController@show');
Route::get('/promocion/{id}/{slug}', 'Participant\PromotionController@show');
Route::match(['get', 'post'], '/facebook/promotion/{id}', 'Participant\PromotionController@requestFbPermissions');
// Participate action
Route::post('/promotion/{id}/go', 'Participant\PromotionController@go');

// Post to fb group
Route::get('/admin/post', 'Creator\PostController@grantPermissions');
Route::get('/admin/posts/callback', 'Creator\PostController@test');

// Admin routes
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('/creators', 'Admin\CreatorController@index');
    Route::get('/creator/{id}/login', 'Admin\FanPageController@logInByUserId');
    Route::get('/creator/{id}/fan-pages', 'Admin\FanPageController@index');
    Route::get('/fan-page/{id}/promotions', 'Admin\PromotionController@index');
    Route::get('/creators/excel', 'Admin\CreatorController@excel');

    Route::get('/referrers', 'Admin\ReferrerController@index');
    Route::get('/referrer/{id}', 'Admin\ReferrerController@show');
    Route::get('/referrer/{id}/fan-pages', 'Admin\ReferrerController@fanPages');
});

// API routes
Route::get('/api/participation/{id}/notes', 'Api\ParticipationController@getNotes');
Route::post('/api/participation/{id}/notes', 'Api\ParticipationController@updateNotes');
Route::post('/api/participation/{id}/stars', 'Api\ParticipationController@updateStars');
Route::post('/api/participation/{id}/status', 'Api\ParticipationController@updateStatus');

// Test route
// It exists only for testing purposes
// The controller will change constantly
Route::get('/test', 'Guess\PromotionController@testQuery');