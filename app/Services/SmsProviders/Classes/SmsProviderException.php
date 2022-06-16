<?php

namespace App\Services\SmsProviders\Classes;

class SmsProviderException extends \Exception
{
    public function __construct($code = 34)
    {
        $reason = match ($code) {
            1 => "The account is inactive.",
            2 => "Process failed",
            3 => "Api Key is invalid",
            4 => "Unknown method",
            5 => "Unknown Get/Post method",
            6 => "Require parameters are empty",
            7 => "Access denied",
            8 => "UserName or Password is wrong",
            9 => "Internal server error,try again",
            10 => "Credit balance is not enough",
            11 => "The target number is not valid",
            12 => "Unable to access to sender Line",
            13 => "Empty or too long Message length",
            14 => "Array length is too long",
            15 => 'Array index is invalid',
            16 => "Invalid IP",
            17 => 'Incorrect date format',
            19 => "Arrays are not equal",
            20 => 'Impossible to insert link',
            22 => 'There are sensitive words',
            24 => 'Template not found',
            26 => 'Premium account only',
            27 => 'The line needs to permission',
            29 => 'Limited IP',
            30 => "Empty array",
            31 => "Unable to get new message",
            32 => "The target number is in black list",
            33 => "Connection lost",
            default => "Unknown error",
        };
        parent::__construct(
            $reason,
            $code
        );
    }
}
