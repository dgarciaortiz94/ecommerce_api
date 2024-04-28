<?php

namespace App\Dashboard\Client\Domain\Services;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\Persist\IClientRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientFinderByEmail
{
    public function __construct(private IClientRepository $repository)
    {
    }

    public function __invoke(string $email): ?AuthenticatedClient
    {
        $client = $this->repository->searchByCriteria(['email.value' => $email])[0] ?? null;

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
