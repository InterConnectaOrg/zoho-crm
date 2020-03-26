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
        $this->comment('Installing Zoho CRM Wrapper...');

        $this->comment('Publishing Zoho CRM OAuth files ...');
        $this->callSilent('vendor:publish', ['--tag' => 'zoho-crm-oauth']);

        $this->comment('Publishing Zoho CRM Configuration ...');
        $this->callSilent('vendor:publish', ['--tag' => 'zoho-crm-config']);

        $this->comment('Publishing Zoho CRM Assets ...');
        $this->callSilent('vendor:publish', ['--tag' => 'zoho-crm-assets']);

        $this->info('Zoho CRM scaffolding installed successfully.');
    }
}
