<?php

namespace Zoho\CRM\Helpers;

trait Credentials
{
    /**
     * [getAllCredentials description]
     * @return [type] [description]
     */
    function getAllCredentials()
    {
        return [
            'client_id' =>  config('zohocrm.client_id'),
            'client_secret' =>  config('zohocrm.client_secret'),
            'redirect_uri' =>  config('zohocrm.redirect_uri'),
            'currentUserEmail' =>  config('zohocrm.current_user_email'),
            'applicationLogFilePath' =>  config('zohocrm.application_log_file_path'),
            'token_persistence_path' =>  config('zohocrm.token_persistence_path'),
            'accounts_url' =>  config('zohocrm.accounts_url'),
            'sandbox' =>  config('zohocrm.sandbox'),
            'apiBaseUrl' =>  config('zohocrm.api_base_url'),
            'apiVersion' =>  config('zohocrm.api_version'),
            'access_type' =>  config('zohocrm.access_type'),
            'persistence_handler_class' =>  config('zohocrm.persistence_handler_class'),
        ];
    }
}
