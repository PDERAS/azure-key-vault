<?php

namespace Pderas\AzureKeyVault;

use Illuminate\Support\ServiceProvider;
use Pderas\AzureKeyVault\Services\AzureClient;
use Pderas\AzureKeyVault\Services\AzureHasher;

class AzureKeyVaultServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/azure_vault.php',
            'azure_vault'
        );

        $this->app->singleton('azure-key-vault', function ($app) {
            $config = $app['config']->get('azure_vault', []);

            $client = new AzureClient($config);
            $hasher = new AzureHasher();

            return new AzureKeyVault($client, $hasher);
        });

    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/azure_vault.php' => config_path('azure_vault.php'),
        ]);
    }
}
