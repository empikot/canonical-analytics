<?php

Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::group(['prefix' => 'landing-page-stats'], function () {
        Route::get('', 'LandingPageStatsController@search');
        Route::post('', 'LandingPageStatsController@store');
        Route::get('/indexability', 'LandingPageIndexabilityController@show');
    });

    Route::group(['prefix' => 'landing-page-weekly-checks'], function() {
        Route::get('','LandingPageWeeklyChecksController@show');
    });
});
