<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithApplication;

use App\Dashboard\Client\Application\SignIn\SignInWithApplication\Utils\SignInWithApplicationCredentials;
use App\Shared\Domain\Bus\Command\ICommandHandler;
use App\Shared\Domain\Bus\Command\ICommandResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class SignInWithApplicationCommandHandler implements ICommandHandler
{
    public function __construct(
        private SignInWithApplicationCase $singInCase,
    ) {
    }

    public function __invoke(SignInWithApplicationCommand $signInWithApplicationQuery): ICommandResponse
    {
        return $this->singInCase->__invoke(
            SignInWithApplicationCredentials::create(
                $signInWithApplicationQuery->email,
                $signInWithApplicationQuery->password
            )
        );
    }
}
