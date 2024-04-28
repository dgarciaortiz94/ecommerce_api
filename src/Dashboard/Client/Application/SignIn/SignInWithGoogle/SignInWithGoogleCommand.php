<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithGoogle;

use App\Shared\Domain\Bus\Command\ICommand;

class SignInWithGoogleCommand implements ICommand
{
    public function __construct(
        public string $code,
        public string $requestedWithHeader
    ) {
    }
}
