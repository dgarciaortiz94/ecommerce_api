<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithApplication\Utils;

use App\Dashboard\Client\Domain\Agregates\ClientEmail;
use App\Dashboard\Client\Domain\Agregates\ClientPlainPassword;

class SignInWithApplicationCredentials
{
    private ClientEmail $email;

    private ClientPlainPassword $password;

    private function __construct()
    {
    }

    public static function create(
        string $email,
        string $password
    ): self {
        $self = new self();

        $self->email = new ClientEmail($email);
        $self->password = new ClientPlainPassword($password);

        return $self;
    }

    public function email(): string
    {
        return $this->email->value();
    }

    public function password(): string
    {
        return $this->password->value();
    }
}
