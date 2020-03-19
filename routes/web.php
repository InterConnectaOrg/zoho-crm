<?php

Route::get('zoho-crm', function () {
    return view('interconnecta/zoho-crm::index');
});

Route::namespace('Zoho\CRM\Http\Controllers')->group(function () {
    Route::post('zoho-crm/save', 'HomeController@store');
    Route::get('zoho-crm/oauthredirect', 'HomeController@index');
});
