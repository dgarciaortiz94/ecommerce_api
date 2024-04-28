<?php

namespace App\Tests\Dashboard\Client\Application;

use App\Dashboard\Client\Application\SignIn\SignInWithApplication\Services\ClientPasswordChecker;
use App\Dashboard\Client\Infrastructure\Persist\MysqlClientRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractClientApplicationMock extends WebTestCase
{
    protected MysqlClientRepository|MockObject|null $repository = null;
    protected ClientPasswordChecker|MockObject|null $clientPasswordChecker = null;

    protected function repository(): MysqlClientRepository|MockObject
    {
        $repository = $this->getMockBuilder(MysqlClientRepository::class)->disableOriginalConstructor()->getMock();

        return $this->repository ??= $repository;
    }
}
