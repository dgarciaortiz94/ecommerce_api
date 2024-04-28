<?php

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\IQuery;
use App\Shared\Domain\Bus\Query\IQueryBus;
use App\Shared\Domain\Bus\Query\IQueryResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyMessengerQueryBus implements IQueryBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function ask(IQuery $query): ?IQueryResponse
    {
        try {
            return $this->bus->dispatch($query)->last(HandledStamp::class)->getResult();
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
