<?php

namespace Zoho\CRM\Console;

use Illuminate\Console\Command;

use Zoho\CRM\Helpers\Credentials;

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;

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
        $grantToken = $this->ask('Please enter your Grant Token');
        if ( !$grantToken ) {
            $this->comment('The Grant Token is required.');
            return;
        }
        try {
            ZCRMRestClient::initialize($this->getAllCredentials());

            $oAuthClient = ZohoOAuth::getClientInstance();
            $oAuthTokens = $oAuthClient->generateAccessToken($grantToken);

            $this->info('Zoho CRM has been set up successfully.');
        } catch (\Exception $e) {
            report($e);
            $this->error($e->getMessage());
        }
    }
}
