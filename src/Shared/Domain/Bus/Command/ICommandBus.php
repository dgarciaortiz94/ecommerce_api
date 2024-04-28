<?php

namespace App\Shared\Domain\Bus\Command;

interface ICommandBus
{
    public function dispatch(ICommand $command): ?ICommandResponse;
}
