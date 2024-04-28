<?php

namespace App\Dashboard\Client\Domain\Agregates;

use App\Shared\Domain\ValueObject\String\StringValueObject;

readonly class ClientHashedPassword extends StringValueObject
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function equals(ClientHashedPassword $password): bool
    {
        return $this->value() === $password->value();
    }
}
