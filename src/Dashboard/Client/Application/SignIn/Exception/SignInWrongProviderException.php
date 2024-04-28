<?php

namespace App\Dashboard\Client\Application\SignIn\Exception;

class SignInWrongProviderException extends \Exception
{
    public function __construct(private string $expectedProvider)
    {
        $this->message = sprintf('Given identifier only authenticable with %s provider', $expectedProvider);
    }
}
