<?php

Route::prefix('api')->group(function () {
    Route::post('/save', 'HomeController@store');
    Route::post('/oauthredirect', 'HomeController@processSecrets');
});

Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('zoho-crm.index');
