<?php

namespace Pderas\AzureKeyVault;

use Illuminate\Support\Arr;
use Pderas\AzureKeyVault\Enums\AzureEndpoint;
use Pderas\AzureKeyVault\Services\AzureClient;
use Pderas\AzureKeyVault\Services\AzureHasher;
use Str;

class AzureKeyVault
{
    public function __construct(
        protected AzureClient $client,
        protected AzureHasher $hasher,
    ) {}

    /**
     * Sign the data using the Azure Key Vault
     */
    public function sign(string $data)
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
     * Verify the signature of the data using the Azure Key Vault
     */
    public function verify(string $value, string $signature, string $key_version)
    {
        $payload = json_encode([
            'alg'     => $this->hasher->getHashingAlgorithm(),
            'digest'  => $this->hasher->hash($value),
            'value'   => $signature,
        ]);

        return $this->client->request(
            AzureEndpoint::VERIFY,
            ['body' => $payload],
            $key_version
        );
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
