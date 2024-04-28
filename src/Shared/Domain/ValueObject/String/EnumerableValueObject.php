<?php

namespace App\Shared\Domain\ValueObject\String;

abstract readonly class EnumerableValueObject
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
        if (!in_array($value, $this->enumerables())) {
            throw new \InvalidArgumentException('"$value" must be one of the enumerables values');
        }

        $this->value = $value;

        return $this;
    }

    abstract protected function enumerables(): array;
}
