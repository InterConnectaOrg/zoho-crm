<?php

namespace Zoho\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Zoho\CRM\ZohoCRM;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // return view('interconnecta/zoho-crm::index');
        return view('interconnecta/zoho-crm::connect', ['zohoCRMJsVariables' => ZohoCRM::jsVariables()]);
    }

    public function redirectHandler(Request $request)
    {
        Cache::add('granttoken', $request->input('code'));

        $this->saveSecrets();

        $credentials = [
            'clientid' => Cache::pull('clientid'),
            'clientsecret' => Cache::pull('clientsecret'),
            'redirecturi' => Cache::pull('redirecturi'),
            'email' => Cache::pull('email'),
        ];

        Cache::pull('granttoken');

        return response($credentials, 200);
    }

    public function store(Request $request)
    {
        if (!file_exists(base_path('.env'))) {
            return response('Please, create a default environment file before setting up the connection.', 404);
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

        return response('Secrets were saved in cache successfully');
    }

    protected function saveSecrets()
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
            $cachedValue = Cache::get($key, '');

            if ($this->keyInFile($envFile, $value)) {
                // If found key, replace value
                $envFile = preg_replace("/^{$value}=.*/m", $value.'='.$cachedValue, $envFile);
            } else {
                // if key is not found, add key and value
                $envFile = $envFile.PHP_EOL."{$value}={$cachedValue}";
            }
        }

        file_put_contents(base_path('.env'), $envFile);

        // $this->initializeZohoCRM();
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
