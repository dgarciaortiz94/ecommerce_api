<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithGoogle;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Shared\Domain\Bus\Command\ICommandHandler;
use App\Shared\Domain\Bus\Command\ICommandResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class RegisterClientWithGoogleCommandHandler implements ICommandHandler
{
    public function __construct(
        private RegisterClientWithGoogleCase $registerClientWithGoogleCase
    ) {
    }

    public function __invoke(RegisterClientWithGoogleCommand $registerClientWithGoogleCommand): ICommandResponse
    {
        $client = AuthenticatedClient::registerWithGoogle(
            $registerClientWithGoogleCommand->name,
            $registerClientWithGoogleCommand->surname,
            $registerClientWithGoogleCommand->email,
            $registerClientWithGoogleCommand->secondSurname,
        );

        return $this->registerClientWithGoogleCase->__invoke($client);
    }
}
