<?php

namespace App\Dashboard\Client\Domain\TokenGenerator;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;

interface ITokenGenerator
{
    public function generateToken(AuthenticatedClient $client): string;
}
