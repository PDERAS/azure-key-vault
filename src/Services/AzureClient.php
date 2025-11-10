<?php

namespace Pderas\AzureKeyVault\Services;

use Illuminate\Support\Arr;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Pderas\AzureKeyVault\Enums\AzureEndpoint;

class AzureClient
{
    /**
     * The base URL for the Azure Key Vault.
     */
    protected string $vault_base_url;

    /**
     * The name of the key in Azure Key Vault.
     */
    protected string $key_name;

    /**
     * The API version to use for Azure Key Vault requests.
     */
    protected static string $api_version = '7.4';

    /**
     * The token provider for obtaining access tokens.
     */
    protected AzureTokenProvider $token_provider;

    public function __construct(array $config = [])
    {
        $this->token_provider = new AzureTokenProvider($config);
        $this->vault_base_url = Arr::get($config, 'vault_base_url', '');
        $this->key_name = Arr::get($config, 'key_name', '');
    }

    /**
     * Set the name of the key in Azure Key Vault.
     */
    public function setKeyName(string $key_name): void
    {
        $this->key_name = $key_name;
    }

    /**
     * Make a request to the Azure Key Vault
     */
    public function request(AzureEndpoint $endpoint, array $options = [], ?string $key_version = null): ?array
    {
        try {
            $token = $this->token_provider->getAccessToken();

            $options['headers'] = [
                'Authorization' => "Bearer {$token}",
                'Content-Type'  => 'application/json',
            ];
            $options['query'] = [
                'api-version' => self::$api_version,
            ];

            $response = Http::send(
                $endpoint->method(),
                $endpoint->path(
                    $this->vault_base_url,
                    $this->key_name,
                    $key_version
                ),
                $options,
            );

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            Log::error($e, [
                'response' => (string) $e->getResponse()->getBody()
            ]);
            throw $e;
        }
    }
}