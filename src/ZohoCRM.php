<?php

namespace Zoho\CRM;

class ZohoCRM
{
    public static function jsVariables()
    {
        return [
            // Relative to current URL
            'webPath' => request()->route()->getPrefix(),

            // Always Relative to Package
            'apiPath' => config('zoho-crm.path', ''),
        ];
    }
}
