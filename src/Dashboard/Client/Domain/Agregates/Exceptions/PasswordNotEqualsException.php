<?php

namespace App\Dashboard\Client\Domain\Agregates\Exceptions;

class PasswordNotEqualsException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Passwords must be the same';
    }
}
