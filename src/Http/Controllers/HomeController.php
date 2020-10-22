<?php

namespace Zoho\CRM\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Log;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use Zoho\CRM\Facades\APIResponse;
use Zoho\CRM\Helpers\Credentials;
use Zoho\CRM\Http\Requests\SaveSecretsFormRequest;
use Zoho\CRM\Http\Requests\StoreFormRequest;
use Zoho\CRM\ZohoCRM;

class HomeController extends Controller
{
    use Credentials;

    /**
     * Return Default App View.
     */
    public function index()
    {
        return view('interconnecta/zoho-crm::index', ['zohoCRMJsVariables' => ZohoCRM::jsVariables()]);
    }

    /**
     * Return View to be Used by Connect.
     */
    public function connect()
    {
        return view('interconnecta/zoho-crm::connect', ['zohoCRMJsVariables' => ZohoCRM::jsVariables()]);
    }

    /**
     * Process Secrets.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processSecrets(SaveSecretsFormRequest $request)
    {
        try {
            $grantToken = $request->input('code');

            $this->initializeZohoCRMClient($grantToken);

            return APIResponse::success(static::getSecrets());
        } catch (\Exception $e) {
            Log::error('Error Initializing Zoho CRM Client via HTTP', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
            ]);

            return APIResponse::fail($e->getMessage());
        }
    }

    /**
     * Save Secrets in environment.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreFormRequest $request)
    {
        try {
            static::validateEnvironmentFile();

            $this->saveSecretsInEnvironmentFile($request);

            return APIResponse::success(null, 'Secrets saved in environment successfully');
        } catch (\Exception $e) {
            return APIResponse::fail($e->getMessage());
        }
    }

    /**
     * Validate A Environment File Exists.
     *
     * @throws \Exception
     */
    protected static function validateEnvironmentFile()
    {
        if (!file_exists(base_path('.env'))) {
            throw new \Exception('Please create a environment file before setting up the connection');
        }
    }

    /**
     * Save Secrets In Environment File.
     *
     * @param string $grantToken
     */
    protected function saveSecretsInEnvironmentFile($request)
    {
        $envFile = file_get_contents(base_path('.env'));

        foreach (static::getZohoCRMVariablesNames() as $key => $secret) {
            $value = $request->input($secret);

            if (static::keyInFile($envFile, $key)) {
                $envFile = preg_replace("/^{$key}=.*/m", $key . '=' . $value, $envFile);
            } else {
                $envFile = $envFile . PHP_EOL . "{$key}={$value}";
            }
        }

        file_put_contents(base_path('.env'), $envFile);
    }

    /**
     * Return Zoho CRM Environment Keys.
     *
     * @return array
     */
    protected static function getZohoCRMVariablesNames()
    {
        return [
            'ZOHO_CRM_CLIENT_ID' => 'clientid',
            'ZOHO_CRM_CLIENT_SECRET' => 'clientsecret',
            'ZOHO_CRM_REDIRECT_URI' => 'redirecturi',
            'ZOHO_CRM_CURRENT_USER_EMAIL' => 'email',
        ];
    }

    /**
     * Intialize Zoho CRM Client.
     *
     * @param string $grantToken
     */
    protected function initializeZohoCRMClient($grantToken)
    {
        try {
            if (!isset($grantToken)) {
                throw new \Exception('The Grant Token is required');
            }

            ZCRMRestClient::initialize($this->getAllCredentials());

            $oAuthClient = ZohoOAuth::getClientInstance();

            $oAuthClient->generateAccessToken($grantToken);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return Zoho CRM Environment Values.
     *
     * @return array
     */
    protected static function getSecrets()
    {
        return [
            'client_id' => config('zoho-crm.client_id'),
            'client_secret' => config('zoho-crm.client_secret'),
            'redirect_uri' => config('zoho-crm.redirect_uri'),
            'email' => config('zoho-crm.current_user_email'),
        ];
    }

    /**
     * Search a String in File.
     *
     * @param string $search
     * @param string $file
     *
     * @return bool
     */
    protected static function keyInFile($file, $search)
    {
        if (strpos($file, $search)) {
            return true;
        }

        return false;
    }
}
