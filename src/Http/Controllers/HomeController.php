<?php

namespace Zoho\CRM\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /*
            http://interconnecta-connect.test/?
            code=1000.1179a48a30d5dc8b14b76cf2901c5a70.7d272a079796779c17dc51d0503174e1
            &location=us
            &accounts-server=https%3A%2F%2Faccounts.zoho.com
            &
        */

        if ($request->get('code') && $request->get('location')) {
            $code = $request->get('code');

            $this->saveTokens();
        } else {
            abort(403, 'Invalid URL');
        }
    }

    public function store(Request $request)
    {
        $clientId = $request->get('clientid');
        $clientSecret = $request->get('clientsecret');
        $redirectUri = $request->get('redirecturi');
        $accessType = $request->get('accesstype');
        $scope = $request->get('scope');

        $requestGrantToken = 'https://accounts.zoho.com/oauth/v2/auth?'
                        .'scope='.$scope
                        .'&client_id='.$clientId
                        .'&response_type=code'
                        .'&access_type='.$accessType
                        .'&redirect_uri='.$redirectUri;

        // Temporarily Store request values in Storage.

        return redirect($requestGrantToken);
    }

    protected function saveTokens()
    {
        // Since we know the Grant Token (code) is valid,
        // Save values in .env file.
    }
}
