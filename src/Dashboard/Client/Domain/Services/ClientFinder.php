<?php

namespace App\Dashboard\Client\Domain\Services;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\Agregates\ClientId;
use App\Dashboard\Client\Domain\Persist\IClientRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientFinder
{
    public function __construct(private IClientRepository $repository)
    {
    }

    public function __invoke(ClientId $id): AuthenticatedClient
    {
        $client = $this->repository->search($id->value());

        $this->checkClientExists($client);

        return $client;
    }

    private function checkClientExists(?AuthenticatedClient $client): void
    {
        if (!$client) {
            throw new NotFoundHttpException('Client not found by this id');
        }
    }
}
