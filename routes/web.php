<?php

// Route::namespace('Zoho\CRM\Http\Controllers')->group(function () {
//     Route::post('zoho-crm/save', 'HomeController@store');
//     Route::get('zoho-crm/oauthredirect', 'HomeController@index');
//     Route::get('zoho-crm/edit', 'HomeController@edit');
// });

Route::group([
    'domain' => null,
    'prefix' => config('zoho-crm.path'),
    'namespace' => '\Zoho\CRM\Http\Controllers',
], function () {
    Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('zoho-crm.index');

    Route::prefix('api')->group(function () {
        Route::post('/save', 'HomeController@store');
        Route::post('/oauthredirect', 'HomeController@redirectHandler');
    });
});
