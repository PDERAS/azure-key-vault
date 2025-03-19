<?php

return [
    /**
     * Azure Key Vault Base URL. Example: https://my-key-vault.vault.azure.net/
     */
    'vault_base_url'    => env('AZURE_KEY_VAULT_BASE_URL'),

    /**
     * Name of the key in Azure Key Vault
     */
    'key_name'          => env('AZURE_KEY_VAULT_KEY_NAME'),

    /**
     * Whether to use the Azure CLI to generate the access token
     */
    'use_azure_cli'     => env('AZURE_KEY_VAULT_USE_AZURE_CLI', false),
];
