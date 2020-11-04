<?php

namespace Zoho\CRM\Console;

use Illuminate\Console\Command;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use Zoho\CRM\Helpers\Credentials;

class SetupCommand extends Command
{
    use Credentials;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-crm:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Zoho CRM';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute Console Command.
     */
    public function handle()
    {
        $grantToken = $this->ask('Please enter your Grant Token...');

        if (!$grantToken) {
            $this->comment('The Grant Token is required.');

            return;
        }

        try {
            ZCRMRestClient::initialize($this->getAllCredentials());

            $oAuthClient = ZohoOAuth::getClientInstance();
            $oAuthTokens = $oAuthClient->generateAccessToken($grantToken);

            $this->info("Zoho CRM has been set up successfully.");
            if($oAuthTokens->getRefreshToken() == null || $oAuthTokens->getRefreshToken() == "NULL") {
                $this->info("There is no Refresh Token in the setup. Please use the Refresh Token command.");
            } else {
                $this->info("This is your Refresh Token: ".$oAuthTokens->getRefreshToken());
            }
        } catch (\Exception $e) {
            report($e);
            $this->error($e->getMessage());
        }
    }
}