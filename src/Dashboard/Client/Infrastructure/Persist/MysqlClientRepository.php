<?php

namespace App\Dashboard\Client\Infrastructure\Persist;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\Persist\IClientRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MysqlClientRepository extends ServiceEntityRepository implements IClientRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthenticatedClient::class);
    }

    public function save(AuthenticatedClient $client): AuthenticatedClient
    {
        $this->getEntityManager()->persist($client);

        $this->getEntityManager()->flush();

        return $client;
    }

    public function remove(AuthenticatedClient $client): void
    {
        $this->getEntityManager()->remove($client);

        $this->getEntityManager()->flush();
    }

    public function search(string $id): AuthenticatedClient
    {
        return $this->getEntityManager()->getRepository(AuthenticatedClient::class)->find($id);
    }

    public function searchByCriteria(array $criteria): array
    {
        return $this->getEntityManager()->getRepository(AuthenticatedClient::class)->findBy($criteria);
    }
}
