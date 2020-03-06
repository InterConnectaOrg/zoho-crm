<?php

namespace Zoho\CRM;

use Illuminate\Support\ServiceProvider;


class ZohoCRMServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'interconnecta/zoho-crm');
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Storage/oauth' => storage_path('app/zoho/crm/oauth'),
            ], 'zoho-crm-oauth');

            $this->publishes([
                __DIR__.'/../config/zoho-crm.php' => config_path('zoho-crm.php'),
            ], 'zoho-crm-config');

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
            __DIR__.'/../config/zoho-crm.php', 'zoho-crm'
        );

        $this->commands([
            Console\InstallCommand::class,
            Console\SetupCommand::class,
        ]);

        $this->app->singleton('zohocrm', function ($app) {
            return new Client();
        });

        $this->app->alias('zohocrm', Client::class);
    }
}
