<?php

namespace Pderas\AzureKeyVault\Enums;

enum AzureEndpoint: string {
    case SIGN = 'sign';
    case VERIFY = 'verify';
    case VERSIONS = 'versions';
    case CURRENT = '';

    /**
     * Get the method name for the endpoint.
     */
    public function method(): string
    {
        return match ($this) {
            self::SIGN, self::VERIFY => 'POST',
            self::VERSIONS, self::CURRENT => 'GET',
            default => 'GET',
        };
    }

    /**
     * Get the full path for the Azure Key Vault endpoint.
     */
    public function path(string $base_url, string $key_name, ?string $version = null): string
    {
        $url = "{$base_url}/keys/{$key_name}";

        // Verify requires a version
        if ($this->requiresVersion()) {
            $url .= "/{$version}";
        }
        
        return "{$url}/{$this->value}";
    }

    /**
     * Check if the endpoint requires a key version.
     */
    private function requiresVersion(): bool
    {
        return $this === self::VERIFY;
    }
}