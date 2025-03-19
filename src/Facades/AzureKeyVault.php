<?php

// Make laravel facade

namespace Pderas\AzureKeyVault\Facades;

use Illuminate\Support\Facades\Facade;

class AzureKeyVault extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'azure-key-vault';
    }
}
