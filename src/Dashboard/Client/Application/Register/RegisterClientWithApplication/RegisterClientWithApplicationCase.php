<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithApplication;

use App\Dashboard\Client\Application\Register\Shared\ClientRegister;
use App\Dashboard\Client\Application\Register\Shared\RegisterClientResponse;
use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;

class RegisterClientWithApplicationCase
{
    public function __construct(private ClientRegister $clientRegister)
    {
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
