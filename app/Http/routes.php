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
Route::get('/clientes/excel', 'Creator\LeadController@excel');
// Welcome message (shown only the first time)
Route::get('/bienvenido-creador', 'Creator\TutorialController@firstWelcome');
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
Route::get('/promotions', function () {
    return redirect('/clubmomy/cuponera', 301);
});
Route::get('/promotions/search', function () {
    return redirect('/clubmomy/cuponera', 301);
});
Route::get('/clubmomy/cuponera', 'Guess\PromotionController@index')->name('cuponera');

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

// Create & schedule posts
Route::get('/facebook/posts', 'Creator\FacebookPostController@index');
Route::get('/facebook/posts/password', 'Creator\FacebookPostController@askPassword');
Route::post('/facebook/posts/password', 'Creator\FacebookPostController@verifyPassword');
Route::get('/facebook/posts/create', 'Creator\FacebookPostController@create');
Route::post('/facebook/posts', 'Creator\FacebookPostController@store');
Route::post('/facebook/posts/images', 'Creator\FacebookPostImageController@store');
Route::get('/facebook/posts/delete', 'Creator\FacebookPostController@destroy');
// Post to fb group
Route::get('/facebook/publish-group-permissions', 'Creator\FbPostPermissionController@grant');
Route::get('/facebook/posts/callback', 'Creator\FbPostPermissionController@callback');
// Get link preview
Route::get('/link-preview', 'Creator\LinkPreviewController@fetch');

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

// Test routes
// It exists only for testing purposes
Route::get('/test/query/promotions', 'Guess\PromotionController@testQuery');