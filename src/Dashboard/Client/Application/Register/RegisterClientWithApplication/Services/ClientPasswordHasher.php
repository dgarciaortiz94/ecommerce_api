<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithApplication\Services;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\Agregates\ClientHashedPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class ClientPasswordHasher
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function __invoke(AuthenticatedClient $client, string $password): AuthenticatedClient
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $client,
            $password
        );

        $client->setPassword(new ClientHashedPassword($hashedPassword));

        return $client;
    }
}
