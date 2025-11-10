<?php

namespace Pderas\AzureKeyVault;

use Illuminate\Support\Arr;
use Pderas\AzureKeyVault\Enums\AzureEndpoint;
use Pderas\AzureKeyVault\Services\AzureClient;
use Pderas\AzureKeyVault\Services\AzureHasher;
use Str;

class AzureKeyVault
{
    /**
     * The encryption algorithm to use for wrapping keys.
     */
    protected string $encryption_algorithm = 'RSA-OAEP-256';

    public function __construct(
        protected AzureClient $client,
        protected AzureHasher $hasher,
    ) {}

    /**
     * Set the key name to use in the Azure Key Vault
     */
    public function key(string $key_name): AzureKeyVault
    {
        $this->client->setKeyName($key_name);
        return $this;
    }

    /**
     * Sign the data using the Azure Key Vault
     * 
     * @return array{signature: string, key_version: string}
     */
    public function sign(string $data): array
    {
        // Reference: https://learn.microsoft.com/en-us/rest/api/keyvault/keys/sign/sign?view=rest-keyvault-keys-7.4
        $payload = json_encode([
            'alg'   => $this->hasher->getHashingAlgorithm(),
            'value' => $this->hasher->hash($data),
        ]);

        $response = $this->client->request(
            AzureEndpoint::SIGN,
            ['body' => $payload]
        );

        return [
            'signature'   => $response['value'],
            'key_version' => Str::afterLast($response['kid'], '/'),
        ];
    }

    /**
     * Wrap a key using the Azure Key Vault
     * 
     * @return array{wrapped_key: string, key_version: string}
     */
    public function wrap(string $data): array
    {
        $payload = json_encode([
            'alg'   => $this->encryption_algorithm,
            'value' => $data,
        ]);

        $response = $this->client->request(
            AzureEndpoint::WRAP,
            ['body' => $payload]
        );

        return [
            'wrapped'     => $response['value'],
            'key_version' => Str::afterLast($response['kid'], '/'),
        ];
    }

    /**
     * Unwrap a key using the Azure Key Vault
     * 
     * @return string|bool The unwrapped key or false on failure
     */
    public function unwrap(string $wrapped_data): string|bool
    {
        $payload = json_encode([
            'alg'   => $this->encryption_algorithm,
            'value' => $wrapped_data,
        ]);

        $response = $this->client->request(
            AzureEndpoint::UNWRAP,
            ['body' => $payload]
        );

        return (bool) Arr::get($response, 'value', false);
    }

    /**
     * Verify the signature of the data using the Azure Key Vault
     */
    public function verify(string $value, string $signature, string $key_version): bool
    {
        $payload = json_encode([
            'alg'     => $this->hasher->getHashingAlgorithm(),
            'digest'  => $this->hasher->hash($value),
            'value'   => $signature,
        ]);

        $response = $this->client->request(
            AzureEndpoint::VERIFY,
            ['body' => $payload],
            $key_version
        );

        return (bool) Arr::get($response, 'value', false);
    }

    /**
     * Get the current key version
     */
    public function currentKeyVersion(): ?string
    {
        $response = $this->client->request(
            AzureEndpoint::CURRENT,
        );
        
        $response_key_url = Arr::get($response, 'key.kid');

        // Return the last part of the URL which is the key version
        return Str::afterLast($response_key_url, '/');
    }
}
