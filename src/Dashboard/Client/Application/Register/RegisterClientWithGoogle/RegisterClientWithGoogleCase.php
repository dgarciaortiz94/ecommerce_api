<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithGoogle;

use App\Dashboard\Client\Application\Register\Shared\ClientRegister;
use App\Dashboard\Client\Application\Register\Shared\RegisterClientResponse;
use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use Symfony\Bundle\SecurityBundle\Security;

class RegisterClientWithGoogleCase
{
    public function __construct(
        private ClientRegister $clientRegister,
        private Security $security
    ) {
    }

    public function __invoke(AuthenticatedClient $client): RegisterClientResponse
    {
        $client = $this->clientRegister->__invoke($client);

        return new RegisterClientResponse(
            $client->getUserIdentifier(),
            $client->name(),
            $client->surname(),
            $client->email(),
            $client->secondSurname(),
        );
    }
}
