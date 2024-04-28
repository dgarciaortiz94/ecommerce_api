<?php

namespace App\Dashboard\Client\Domain\Persist;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;

interface IClientRepository
{
    public function save(AuthenticatedClient $Client): AuthenticatedClient;

    public function remove(AuthenticatedClient $Client): void;

    public function search(string $id): AuthenticatedClient;

    public function searchByCriteria(array $criteria): array;
}
