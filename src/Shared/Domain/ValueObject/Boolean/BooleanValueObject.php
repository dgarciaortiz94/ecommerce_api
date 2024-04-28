<?php

namespace App\Shared\Domain\ValueObject\Boolean;

abstract readonly class BooleanValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->setValue($value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    private function setValue(bool $value)
    {
        $this->value = $value;

        return $this;
    }
}
