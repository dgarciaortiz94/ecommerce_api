<?php

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\Bus\Command\ICommand;
use App\Shared\Domain\Bus\Command\ICommandBus;
use App\Shared\Domain\Bus\Command\ICommandResponse;
use App\Shared\Domain\Bus\Query\IQuery;
use App\Shared\Domain\Bus\Query\IQueryBus;
use App\Shared\Domain\Bus\Query\IQueryResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct(
        private IQueryBus $queryBus,
        private ICommandBus $iCommandBus
    ) {
    }

    public function ask(IQuery $query): IQueryResponse
    {
        return $this->queryBus->ask($query);
    }

    public function dispatch(ICommand $command): ICommandResponse
    {
        return $this->iCommandBus->dispatch($command);
    }
}
