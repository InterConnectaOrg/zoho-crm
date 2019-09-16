<?php

namespace Zoho\CRM;

use Illuminate\Support\ServiceProvider;


class ZohoCRMServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Storage/oauth' => storage_path('app/zoho/crm/oauth'),
            ], 'zohocrm-oauth');
            $this->publishes([
                __DIR__.'/../config/zohocrm.php' => config_path('zohocrm.php'),
            ], 'zohocrm-config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/zohocrm.php', 'zohocrm'
        );

        $this->commands([
            Console\InstallCommand::class,
            Console\SetupCommand::class,
        ]);

        $this->app->bind('zohocrm', function ()
        {
            return $this->app->make('Zoho\CRM\Client');
        });
    }
}
