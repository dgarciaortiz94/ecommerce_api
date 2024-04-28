<?php

namespace App\Shared\Domain\ValueObject\Uid;

use Symfony\Component\Uid\Uuid;

abstract readonly class UuidValueObject
{
    protected string $value;

    public function __construct(?string $value = null)
    {
        $this->setValue($value ?? Uuid::v4());
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
