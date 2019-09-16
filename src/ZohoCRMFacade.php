<?php

namespace Zoho\CRM;

use Illuminate\Support\Facades\Facade;

class ZohoCRMFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zohocrm';
    }
}
