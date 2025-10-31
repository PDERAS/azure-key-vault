<?php

use Illuminate\Support\Str;
use Pderas\AzureKeyVault\AzureKeyVault;
use Pderas\AzureKeyVault\Services\AzureClient;
use Pderas\AzureKeyVault\Services\AzureHasher;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $config = app('config')->get('azure-key-vault');

    // Instantiate real dependencies for integration
    $client = new AzureClient($config);
    $hasher = new AzureHasher();
    $vault = new AzureKeyVault($client, $hasher);

    $this->vault = $vault;
});

it('signs data and returns signature and key version', function () {
    $plaintext = Str::random(32);
    $signed = $this->vault->sign($plaintext);

    expect($signed)->toHaveKey('signature')
                   ->toHaveKey('key_version');
});

it('signs data and is verified successfully', function () {
    $plaintext = Str::random(32);
    $signed = $this->vault->sign($plaintext);

    $verified = $this->vault->verify(
        $plaintext,
        $signed['signature'],
        $signed['key_version']
    );

    expect($verified)->toBeTrue();
});

it('wraps data', function () {
    $plaintext = Str::random(40);

    $wrapped = $this->vault->wrap($plaintext);

    expect($wrapped)->toHaveKey('wrapped')
                    ->toHaveKey('key_version');
});

it('unwraps data successfully', function () {
    $plaintext = Str::random(40);

    $wrapped = $this->vault->wrap($plaintext);

    $unwrapped = $this->vault->unwrap(
        $wrapped['wrapped'],
        $wrapped['key_version'],
    );

    expect($unwrapped)->toEqual($plaintext);
});