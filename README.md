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

### Publish config file (optional)
```sh
php artisan vendor:publish --provider="Pderas\AzureKeyVault\AzureKeyVaultServiceProvider"
```
# Authentication

## Managed Identity (default)
Ensure that the hosting server is an Azure VM with the correct permissions set for the server user.

## Azure CLI
Set the env variable to use the Azure CLI
```ini
AZURE_KEY_VAULT_USE_AZURE_CLI=true
```
#### CLI Installation (if necessary)
To use Azure CLI, you must install it by running the following command
```sh
curl -sL https://aka.ms/InstallAzureCLIDeb | sudo bash
```

Then, authenticate yourself with Azure
```sh
az login
```

# Usage
### Setup
Set the following variable in the project's env
```ini
AZURE_KEY_VAULT_BASE_URL="https://your-key-vault.vault.azure.net"
```

If only a single key is being used, you can define it in the env as well
```ini
AZURE_KEY_VAULT_KEY_NAME="your-key-name"
```

### Examples

```php
use Pderas\AzureKeyVault\Facades\AzureKeyVault;

$data = Str::random(2000);

// Using single key defined in env
AzureKeyVault::sign($data);

// Using alternate key
AzureKeyVault::key('alternate-key')->sign($data);
```