<?php

Route::group([
    'domain' => null,
    'prefix' => config('zoho-crm.path'),
    'namespace' => '\Zoho\CRM\Http\Controllers',
], function () {
    Route::prefix('api')->group(function () {
        Route::post('/save', 'HomeController@store');
        Route::post('/oauthredirect', 'HomeController@processSecrets');
    });
});
