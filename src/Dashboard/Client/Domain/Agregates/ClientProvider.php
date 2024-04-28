<?php

namespace App\Dashboard\Client\Domain\Agregates;

use App\Shared\Domain\ValueObject\String\EnumerableValueObject;

readonly class ClientProvider extends EnumerableValueObject
{
    public const APPLICATION = 'Application';
    public const GOOGLE = 'Google';

    protected function enumerables(): array
    {
        return [
            self::APPLICATION,
            self::GOOGLE,
        ];
    }
}
