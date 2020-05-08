<?php

namespace Zoho\CRM\Console;

use Illuminate\Console\Command;

use Zoho\CRM\Helpers\Credentials;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;

class RefreshTokenCommand extends Command
{
    use Credentials;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-crm:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Second setup of Zoho CRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [FunctionName description]
     * @param string $value [description]
     */
    public function handle()
    {
        $refreshToken = $this->ask('Please enter your Refresh Token');
        if ( !$refreshToken ) {
            $this->comment('The Refresh Token is required.');
            return;
        }
        $userEmailId = $this->ask('Please enter your user email id');
        if ( !$userEmailId ) {
            $this->comment('The User Email is required.');
            return;
        }
        try {
            ZCRMRestClient::initialize($this->getAllCredentials());

            $oAuthClient = ZohoOAuth::getClientInstance();
            $oAuthTokens = $oAuthClient->generateAccessTokenFromRefreshToken($refreshToken, $userEmailId);

            $this->info('Zoho CRM has been set up successfully.');
        } catch (\Exception $e) {
            report($e);
            $this->error($e->getMessage());
        }
    }
}
