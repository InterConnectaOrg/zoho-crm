<?php

return [
    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'client_id' => env('ZOHO_CRM_CLIENT_ID', null),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'client_secret' => env('ZOHO_CRM_CLIENT_SECRET', null),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'redirect_uri' => env('ZOHO_CRM_REDIRECT_URI', null),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'current_user_email' => env('ZOHO_CRM_CURRENT_USER_EMAIL', null),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'application_log_file_path' => storage_path('app/zoho/crm/oauth/logs'),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'token_persistence_path' => storage_path('app/zoho/crm/oauth/tokens'),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'accounts_url' => env('ZOHO_CRM_ACCOUNTS_URL', 'https://accounts.zoho.com'),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'sandbox' => env('ZOHO_CRM_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'api_base_url' => env('ZOHO_CRM_API_BASE_URL', 'www.zohoapis.com'),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'api_version' => env('ZOHO_CRM_API_VERSION', 'v2'),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'access_type' => env('ZOHO_CRM_ACCESS_TYPE', 'offline'),

    /*
    |--------------------------------------------------------------------------
    | title
    |--------------------------------------------------------------------------
    |
    | description
    |
    */

    'persistence_handler_class' => env('ZOHO_CRM_PERSISTENCE_HANDLER_CLASS', 'ZohoOAuthPersistenceHandler'),

    /*
    |--------------------------------------------------------------------------
    | Package Name
    |--------------------------------------------------------------------------
    |
    | Custom Attribute to be used by Connect Manager package.
    |
    */

    'package_name' => 'zoho-crm',

    /*
    |--------------------------------------------------------------------------
    | Package Label
    |--------------------------------------------------------------------------
    |
    | Custom Attribute to be used by Connect Manager package.
    |
    */

    'package_label' => 'Zoho CRM Wrapper',

    /*
    |--------------------------------------------------------------------------
    | Package Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where the package will be accessible from. If this
    | setting is null, Connect Manager will reside under the same domain as the
    | application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Package Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the package routes will be accessible from. Feel free
    | to change this path to anything you like.
    */

    'path' => 'zoho-crm',
];
