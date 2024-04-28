<?php

namespace App\Shared\Domain\ValueObject\String;

use PharIo\Manifest\InvalidEmailException;

abstract readonly class EmailValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function setValue(string $value)
    {
        $this->validate($value);

        $this->value = $value;

        return $this;
    }

    private function validate(string $value): void
    {
        if (!preg_match(
            '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',
            $value
        )) {
            throw new InvalidEmailException('Email is not valid');
        }
    }
}
