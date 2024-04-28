<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithGoogle;

use App\Shared\Domain\Bus\Command\ICommand;

class RegisterClientWithGoogleCommand implements ICommand
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public ?string $secondSurname = null
    ) {
    }
}
