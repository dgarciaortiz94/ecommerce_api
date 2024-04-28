<?php

namespace App\Dashboard\Client\Application\Register\Shared;

use App\Shared\Domain\Bus\Command\ICommandResponse;

class RegisterClientResponse implements ICommandResponse
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $surname,
        public string $email,
        public ?string $secondSurname = null,
    ) {
    }
}
