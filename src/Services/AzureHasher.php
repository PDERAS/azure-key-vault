<?php

namespace Pderas\AzureKeyVault\Services;

class AzureHasher
{
    /**
     * The hashing algorithm to use for signing.
     */
    private string $hashing_algorithm;

    public function __construct()
    {
        $this->hashing_algorithm = config('azure_vault.algorithm', 'RS256');
    }

    /**
     * Hash the data and return the base64 encoded value
     */
    public function hash(string $data): string
    {
        // Use binary encoded hash
        $hashed_value = hash($this->getHashAlgorithm(), $data, true);

        return base64_encode($hashed_value);
    }

    /**
     * Get the hashing algorithm used for signing.
     */
    public function getHashingAlgorithm(): string
    {
        return $this->hashing_algorithm;
    }

    /**
     * Get the hashing algorithm based on the configuration.
     */
    protected function getHashAlgorithm(): string
    {
        // Supported Azure algorithms mapped to PHP hash functions
        $algorithm_map = [
            'RS256' => 'sha256',
            'RS384' => 'sha384',
            'RS512' => 'sha512',
        ];

        if (!array_key_exists($this->hashing_algorithm, $algorithm_map)) {
            throw new \InvalidArgumentException("Unsupported hashing algorithm: {$this->hashing_algorithm}");
        }

        return $algorithm_map[$this->hashing_algorithm] ?? 'sha256';
    }
}