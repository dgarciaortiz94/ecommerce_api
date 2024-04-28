<?php

namespace App\Dashboard\Client\Application\Register\RegisterClientWithApplication;

use App\Shared\Domain\Bus\Command\ICommand;

class RegisterClientWithApplicationCommand implements ICommand
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $password,
        public string $repeatedPassword,
        public ?string $secondSurname = null
    ) {
    }
}
