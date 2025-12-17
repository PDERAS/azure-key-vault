<?php

namespace Pderas\AzureKeyVault\Enums;

enum AzureEndpoint: string {
    case SIGN = 'sign';
    case VERIFY = 'verify';
    case VERSIONS = 'versions';
    case WRAP = 'wrapkey';
    case UNWRAP = 'unwrapkey';
    case CURRENT = '';

    /**
     * Get the method name for the endpoint.
     */
    public function method(): string
    {
        return match ($this) {
            self::SIGN, self::VERIFY, self::WRAP, self::UNWRAP => 'POST',
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
        if (!is_null($version)) {
            $url .= "/{$version}";
        }
        
        return "{$url}/{$this->value}";
    }
}