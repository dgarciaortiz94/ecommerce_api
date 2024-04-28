<?php

namespace App\Dashboard\Client\Application\Register\Shared;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\Persist\IClientRepository;
use App\Dashboard\Client\Domain\Services\ClientFinderByEmail;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ClientRegister
{
    public function __construct(
        private IClientRepository $repository,
        private ClientFinderByEmail $finder
    ) {
    }

    public function __invoke(AuthenticatedClient $client): AuthenticatedClient
    {
        $this->validateExistentClient($client->email());

        return $this->repository->save($client);
    }

    public function validateExistentClient(string $email)
    {
        try {
            if ($this->finder->__invoke($email)) {
                throw new ConflictHttpException('This client is already created');
            }
        } catch (NotFoundHttpException $e) {
        }
    }
}
