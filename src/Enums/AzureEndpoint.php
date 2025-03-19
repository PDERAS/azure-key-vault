<?php

namespace Pderas\AzureKeyVault\Enums;

enum AzureEndpoint: string {
    case SIGN = 'sign';
    case VERIFY = 'verify';
    case ENCRYPT = 'encrypt';
    case DECRYPT = 'decrypt';
    case VERSIONS = 'versions';
}