<?php

namespace Pderas\AzureKeyVault;

use AzureOss\FlysystemAzureBlobStorage\AzureBlobStorageAdapter;
use AzureOss\Storage\Blob\BlobContainerClient;
use AzureOss\Storage\Blob\BlobServiceClient;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
// use MicrosoftAzure\Storage\Blob\BlobRestProxy;

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

        $this->app->singleton('azure-key-vault', function () {
            return new AzureKeyVault();
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
