<?php

namespace App\Shared\Domain\ValueObject\String;

abstract readonly class NullableStringValueObject
{
    protected ?string $value;

    public function __construct(?string $value = null)
    {
        $this->setValue($value);
    }

    public function value(): ?string
    {
        return $this->value;
    }

    private function setValue(?string $value)
    {
        $this->value = $value;

        return $this;
    }
}
