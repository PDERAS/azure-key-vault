# An Azure Key Vault package for Laravel
This package provides

# Installation
Add repository to `composer.json`
```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:PDERAS/azure-key-vault.git"
    },
]
```

### Install via composer 
```sh
composer require pderas/azure-key-vault
```

### Publish config file
```sh
php artisan vendor:publish --provider="Pderas\AzureKeyVault\AzureKeyVaultServiceProvider"
```

### Azure CLI
If using Azure CLI, you must install it by running the following command
```sh
curl -sL https://aka.ms/InstallAzureCLIDeb | sudo bash
```

Then, authenticate yourself with Azure
```sh
az login
```

# Usage
```php
use Pderas\AzureKeyVault\Facades\AzureKeyVault;

//...

AzureKeyVault::sign(Str::random(2000));
```