<?php

namespace App\Shared\Domain\Bus\Query;

interface IQueryBus
{
    public function ask(IQuery $query): ?IQueryResponse;
}
