<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Pderas\AzureKeyVault\AzureKeyVaultServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            AzureKeyVaultServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Optional: set config defaults for testing
        $app['config']->set('azure-key-vault', [
            'vault_base_url'    => env('AZURE_KEY_VAULT_BASE_URL'),
            'key_name'          => env('AZURE_KEY_VAULT_KEY_NAME'),
            'algorithm'         => env('AZURE_KEY_VAULT_ALGORITHM', 'RS256'),
            'use_azure_cli'     => env('AZURE_KEY_VAULT_USE_AZURE_CLI', false),
        ]);
    }
}
