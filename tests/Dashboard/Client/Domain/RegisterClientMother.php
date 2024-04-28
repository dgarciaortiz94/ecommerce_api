<?php

namespace App\Tests\Dashboard\Client\Domain;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;

class RegisterClientMother
{
    private AuthenticatedClient $client;

    private function __construct(
        string $name = 'Fake Client',
        string $surname = 'Surname',
        string $email = 'mail@mail.com',
        string $plainPassword = '@Prueba123',
        string $repeatedPlainPassword = '@Prueba123',
        ?string $secondSurname = 'Second Surname',
    ) {
        $this->client = AuthenticatedClient::register(
            $name,
            $surname,
            $email,
            $plainPassword,
            $repeatedPlainPassword,
            $secondSurname
        );
    }

    public static function register(
        string $name = 'Fake Client',
        string $surname = 'Surname',
        string $email = 'mail@mail.com',
        string $plainPassword = '@Prueba123',
        string $repeatedPlainPassword = '@Prueba123',
        ?string $secondSurname = 'Second Surname',
    ): AuthenticatedClient {
        $self = new self(
            $name,
            $surname,
            $email,
            $plainPassword,
            $repeatedPlainPassword,
            $secondSurname
        );

        return $self->client;
    }

    public static function registerWithGoogle(
        string $name = 'Fake Client',
        string $surname = 'Surname',
        string $email = 'mail@gmail.com',
        string $plainPassword = '@Prueba123',
        string $repeatedPlainPassword = '@Prueba123',
        ?string $secondSurname = 'Second Surname',
    ): AuthenticatedClient {
        $client = AuthenticatedClient::registerWithGoogle(
            $name,
            $surname,
            $email,
            $plainPassword,
            $repeatedPlainPassword,
            $secondSurname,
        );

        return $client;
    }
}
