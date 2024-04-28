<?php

namespace App\Shared\Domain\ValueObject\Date;

abstract readonly class DatetimeImmutableValueObject
{
    protected \DateTimeImmutable $value;

    public function __construct(\DateTimeImmutable $value)
    {
        $this->setValue($value);
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }

    private function setValue(\DateTimeImmutable $value)
    {
        $this->value = $value;

        return $this;
    }
}
