<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithApplication\Services;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientPasswordChecker
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function __invoke(AuthenticatedClient $client, string $password): bool
    {
        $passwordMatches = $this->passwordHasher->isPasswordValid($client, $password);

        $this->checkPasswordMatches($passwordMatches);

        return $password;
    }

    private function checkPasswordMatches(bool $passwordMatches): void
    {
        if (!$passwordMatches) {
            throw new InvalidPasswordException('Password is not correct');
        }
    }
}
