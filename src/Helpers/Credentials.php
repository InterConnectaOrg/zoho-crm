<?php

namespace Zoho\CRM\Helpers;

use Illuminate\Support\Facades\Cache;

trait Credentials
{
    /**
     * Get Zoho CRM SDK Credentials.
     *
     * @return array
     */
    public function getAllCredentials()
    {
        return [
            'client_id' => config('zoho-crm.client_id'),
            'client_secret' => config('zoho-crm.client_secret'),
            'redirect_uri' => config('zoho-crm.redirect_uri'),
            'currentUserEmail' => config('zoho-crm.current_user_email'),
            'applicationLogFilePath' => config('zoho-crm.application_log_file_path'),
            'token_persistence_path' => config('zoho-crm.token_persistence_path'),
            'accounts_url' => config('zoho-crm.accounts_url'),
            'sandbox' => config('zoho-crm.sandbox'),
            'apiBaseUrl' => config('zoho-crm.api_base_url'),
            'apiVersion' => config('zoho-crm.api_version'),
            'access_type' => config('zoho-crm.access_type'),
            'persistence_handler_class' => config('zoho-crm.persistence_handler_class'),
        ];
    }

    /**
     * Get Cached ZOHO CRM SDK Credentials.
     *
     * @return array
     */
    public function getCachedCredentials()
    {
        return [
            'client_id' => Cache::get('clientid', ''),
            'client_secret' => Cache::get('clientsecret', ''),
            'redirect_uri' => Cache::get('redirecturi', ''),
            'currentUserEmail' => Cache::get('email', ''),
            'applicationLogFilePath' => config('zoho-crm.application_log_file_path'),
            'token_persistence_path' => config('zoho-crm.token_persistence_path'),
            'accounts_url' => config('zoho-crm.accounts_url'),
            'sandbox' => config('zoho-crm.sandbox'),
            'apiBaseUrl' => config('zoho-crm.api_base_url'),
            'apiVersion' => config('zoho-crm.api_version'),
            'access_type' => config('zoho-crm.access_type'),
            'persistence_handler_class' => config('zoho-crm.persistence_handler_class'),
        ];
    }
}
