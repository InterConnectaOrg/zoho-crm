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
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->registerPublishing();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/zoho-crm.php',
            'zoho-crm'
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

    /**
     * Register the Zoho CRM Routes.
     */
    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register the Zoho CRM Resources.
     */
    private function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'interconnecta/zoho-crm');
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Storage/oauth' => storage_path('app/zoho/crm/oauth'),
            ], 'zoho-crm-oauth');

            $this->publishes([
                __DIR__.'/../config/zoho-crm.php' => config_path('zoho-crm.php'),
            ], 'zoho-crm-config');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/zoho-crm'),
            ], 'zoho-crm-assets');
        }
    }
}
