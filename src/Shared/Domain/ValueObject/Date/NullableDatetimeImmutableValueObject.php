<?php

namespace App\Shared\Domain\ValueObject\Date;

abstract readonly class NullableDatetimeImmutableValueObject
{
    protected ?\DateTimeImmutable $value;

    public function __construct(?\DateTimeImmutable $value = null)
    {
        $this->setValue($value);
    }

    public function value(): ?\DateTimeImmutable
    {
        return $this->value;
    }

    private function setValue(?\DateTimeImmutable $value)
    {
        $this->value = $value;

        return $this;
    }
}
