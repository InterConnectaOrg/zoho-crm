<?php

namespace Zoho\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        /*
            http://interconnecta-connect.test/?
            code=1000.1179a48a30d5dc8b14b76cf2901c5a70.7d272a079796779c17dc51d0503174e1
            &location=us
            &accounts-server=https%3A%2F%2Faccounts.zoho.com
            &
        */

        if ($request->has('code', 'location')) {
            Cache::add('granttoken', $request->query('code'));

            $this->saveTokens();
        } else {
            abort(403, 'Invalid URL');
        }
    }

    public function store(Request $request)
    {
        if (!file_exists(base_path('.env'))) {
            return response('Please, create an environment file before setting up the connection.', 404);
        }

        $clientId = $request->input('clientid');
        $clientSecret = $request->input('clientsecret');
        $redirectUri = $request->input('redirecturi');
        $accessType = $request->input('accesstype');
        $scope = $request->input('scope');
        $email = $request->input('email');

        Cache::add('clientid', $clientId);
        Cache::add('clientsecret', $clientSecret);
        Cache::add('redirecturi', $redirectUri);
        Cache::add('email', $email);

        $requestGrantToken = 'https://accounts.zoho.com/oauth/v2/auth?'
                        .'scope='.$scope
                        .'&client_id='.$clientId
                        .'&response_type=code'
                        .'&access_type='.$accessType
                        .'&redirect_uri='.$redirectUri;

        return redirect($requestGrantToken);
    }

    protected function saveTokens()
    {
        // Given the Grant Token (code) is valid

        // Then Save keys in .env file

        $credentials = [
            'clientid' => 'ZOHO_CRM_CLIENT_ID',
            'clientsecret' => 'ZOHO_CRM_CLIENT_SECRET',
            'redirecturi' => 'ZOHO_CRM_REDIRECT_URI',
            'email' => 'ZOHO_CRM_CURRENT_USER_EMAIL',
            'granttoken' => 'ZOHO_CRM_GRANT_TOKEN',
        ];

        $envFile = file_get_contents(base_path('.env'));

        foreach ($credentials as $key => $value) {
            $cachedValue = Cache::pull($key, '');

            if ($this->keyInFile($envFile, $value)) {
                // If found key, replace value
                $envFile = preg_replace("/^{$value}=.*/m", $value.'='.$cachedValue, $envFile);
            } else {
                // if key is not found, add key and value
                $envFile = $envFile.PHP_EOL."{$value}={$cachedValue}";
            }
        }

        file_put_contents(base_path('.env'), $envFile);
    }

    /**
     * Search a String in File.
     *
     * @param string $search
     * @param string $file
     *
     * @return bool
     */
    protected function keyInFile($file, $search)
    {
        if (strpos($file, $search)) {
            return true;
        }

        return false;
    }
}
