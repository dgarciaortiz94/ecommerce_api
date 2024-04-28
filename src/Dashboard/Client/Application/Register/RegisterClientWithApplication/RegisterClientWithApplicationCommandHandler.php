<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithApplication;

use App\Dashboard\Client\Application\Register\RegisterClientWithApplication\Services\ClientPasswordHasher;
use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Shared\Domain\Bus\Command\ICommandHandler;
use App\Shared\Domain\Bus\Command\ICommandResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class RegisterClientWithApplicationCommandHandler implements ICommandHandler
{
    public function __construct(
        private RegisterClientWithApplicationCase $registerClientCase,
        private ClientPasswordHasher $passwordHasher
    ) {
    }

    public function __invoke(RegisterClientWithApplicationCommand $registerClientCommand): ICommandResponse
    {
        $client = AuthenticatedClient::register(
            $registerClientCommand->name,
            $registerClientCommand->surname,
            $registerClientCommand->email,
            $registerClientCommand->password,
            $registerClientCommand->repeatedPassword,
            $registerClientCommand->secondSurname,
        );

        $client = $this->passwordHasher->__invoke($client, $registerClientCommand->password);

        return $this->registerClientCase->__invoke($client);
    }
}
