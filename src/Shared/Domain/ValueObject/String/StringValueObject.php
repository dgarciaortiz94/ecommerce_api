<?php

namespace App\Shared\Domain\ValueObject\String;

abstract readonly class StringValueObject
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
        $this->value = $value;

        return $this;
    }
}
