<?php

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\ICommand;
use App\Shared\Domain\Bus\Command\ICommandBus;
use App\Shared\Domain\Bus\Command\ICommandResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyMessengerCommandBus implements ICommandBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function dispatch(ICommand $command): ?ICommandResponse
    {
        try {
            return $this->bus->dispatch($command)->last(HandledStamp::class)->getResult();
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
