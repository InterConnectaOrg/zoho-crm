<?php

Route::group([
    'domain' => null,
    'prefix' => config('zoho-crm.path'),
    'namespace' => '\Zoho\CRM\Http\Controllers',
], function () {
    Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('zoho-crm.index');
});
