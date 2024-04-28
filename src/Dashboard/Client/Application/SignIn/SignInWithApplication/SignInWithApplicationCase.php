<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithApplication;

use App\Dashboard\Client\Application\SignIn\Exception\SignInWrongProviderException;
use App\Dashboard\Client\Application\SignIn\Shared\SignInProfileResponse;
use App\Dashboard\Client\Application\SignIn\Shared\SignInResponse;
use App\Dashboard\Client\Application\SignIn\SignInWithApplication\Services\ClientPasswordChecker;
use App\Dashboard\Client\Application\SignIn\SignInWithApplication\Utils\SignInWithApplicationCredentials;
use App\Dashboard\Client\Domain\Agregates\ClientProvider;
use App\Dashboard\Client\Domain\Services\ClientFinderByEmail;
use App\Dashboard\Client\Domain\TokenGenerator\ITokenGenerator;

class SignInWithApplicationCase
{
    public function __construct(
        private ClientFinderByEmail $clientFinderByEmail,
        private ClientPasswordChecker $passwordChecker,
        private ITokenGenerator $tokenGenerator
    ) {
    }

    public function __invoke(SignInWithApplicationCredentials $credentials): SignInResponse
    {
        $client = $this->clientFinderByEmail->__invoke($credentials->email());

        if (!$client->isApplicationProvider()) {
            throw new SignInWrongProviderException(ClientProvider::APPLICATION);
        }

        $this->passwordChecker->__invoke($client, $credentials->password());

        $token = $this->tokenGenerator->generateToken($client);

        $response = new SignInResponse(
            new SignInProfileResponse(
                $client->getUserIdentifier(),
                $client->name(),
                $client->surname(),
                $client->email(),
                $client->getRoles(),
                $client->secondSurname(),
            ),
            $token
        );

        return $response;
    }
}
