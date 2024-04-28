<?php

namespace App\Dashboard\Client\Domain\Agregates;

use App\Dashboard\Client\Domain\Agregates\Exceptions\InvalidPasswordFormatException;
use App\Shared\Domain\ValueObject\String\StringValueObject;

readonly class ClientPlainPassword extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->checkFormat($value);

        parent::__construct($value);
    }

    /*
     * Regular expression must match:
     * - 8 characters
     * - 1 cappital leter
     * - 1 lowercase letter
     * - 1 symbol
     */
    public function checkFormat(string $value): void
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[1-9])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).{8,}$/', $value)) {
            throw new InvalidPasswordFormatException('Password must contains at least 8 characters, 1 cappital leter, 1 lowercase letter, 1 number, and 1 symbol');
        }
    }

    public function equals(ClientPlainPassword $password): bool
    {
        return $this->value() === $password->value();
    }
}
