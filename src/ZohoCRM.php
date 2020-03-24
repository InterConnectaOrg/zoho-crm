<?php

namespace Zoho\CRM;

class ZohoCRM
{
    public static function jsVariables()
    {
        // return [
        //     'path' => config('zoho-crm.path', ''),
        // ];
        return [
            // app/zoho-crm or /zoho-crm (relative to URL)
            'webPath' => request()->route()->getPrefix(),
            // zoho-crm (always relative to package)
            'apiPath' => config('zoho-crm.path', ''),
        ];
    }
}
