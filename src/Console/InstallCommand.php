<?php

namespace Zoho\CRM\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho-crm:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Zoho CRM resources';

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
        $this->comment('Publishing Zoho CRM OAuth files ...');
        $this->callSilent('vendor:publish', ['--tag' => 'zoho-crm-oauth']);

        $this->comment('Publishing Zoho CRM Configuration ...');
        $this->callSilent('vendor:publish', ['--tag' => 'zoho-crm-config']);

        $this->info('Zoho CRM scaffolding installed successfully.');
    }
}
