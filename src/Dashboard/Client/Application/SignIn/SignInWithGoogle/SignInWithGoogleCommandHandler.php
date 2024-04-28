<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithGoogle;

use App\Shared\Domain\Bus\Command\ICommandHandler;
use App\Shared\Domain\Bus\Command\ICommandResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class SignInWithGoogleCommandHandler implements ICommandHandler
{
    public function __construct(
        private SignInWithGoogleCase $signInWithGoogleCase,
    ) {
    }

    public function __invoke(SignInWithGoogleCommand $signInWithGoogleQuery): ICommandResponse
    {
        return $this->signInWithGoogleCase->__invoke(
            $signInWithGoogleQuery->code,
            $signInWithGoogleQuery->requestedWithHeader
        );
    }
}
