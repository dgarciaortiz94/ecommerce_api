<?php

namespace App\Tests\Dashboard\Client\Application\RegisterClient;

use App\Dashboard\Client\Application\Register\RegisterClientWithApplication\RegisterClientWithApplicationCase;
use App\Dashboard\Client\Application\Register\Shared\ClientRegister;
use App\Dashboard\Client\Application\Register\Shared\RegisterClientResponse;
use App\Dashboard\Client\Domain\Agregates\Exceptions\InvalidPasswordFormatException;
use App\Dashboard\Client\Domain\Agregates\Exceptions\PasswordNotEqualsException;
use App\Dashboard\Client\Domain\Services\ClientFinderByEmail;
use App\Tests\Dashboard\Client\Application\AbstractClientApplicationMock;
use App\Tests\Dashboard\Client\Domain\RegisterClientMother;
use PharIo\Manifest\InvalidEmailException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RegisterClientTest extends AbstractClientApplicationMock
{
    protected ClientFinderByEmail|MockObject|null $clientFinderByEmail = null;

    /**
     * @test
     */
    public function shouldRegisterClientSuccessfully()
    {
        $client = RegisterClientMother::register();

        $this->repository()->expects($this->once())
            ->method('save')
            ->willReturn($client);

        $registerClientCase = new RegisterClientWithApplicationCase(new ClientRegister($this->repository(), $this->clientFinderByEmail()));

        $registerClientResponse = $registerClientCase->__invoke($client);

        $this->assertEquals(RegisterClientResponse::class, $registerClientResponse::class);
    }

    /**
     * @test
     */
    public function shouldReturnConflictResponse()
    {
        $client = RegisterClientMother::register(
            email: 'dgarciaortiz94@gmail.com',
            plainPassword: '@Prueba123',
            repeatedPlainPassword: '@Prueba123'
        );

        $clientFinderByEmail = $this->clientFinderByEmail();

        $clientFinderByEmail->expects($this->once())
            ->method('__invoke')
            ->willReturn($client);

        $this->repository();

        $registerClientCase = new RegisterClientWithApplicationCase(new ClientRegister($this->repository(), $this->clientFinderByEmail()));

        $this->expectException(ConflictHttpException::class);

        $registerClientResponse = $registerClientCase->__invoke($client);
    }

    /**
     * @test
     */
    public function shouldThrowInvalidEmailException()
    {
        $this->expectException(InvalidEmailException::class);

        $wrongEmailClient = RegisterClientMother::register(email: 'this-is-not-mail');
    }

    /**
     * @test
     */
    public function shouldThrowInvalidPasswordException()
    {
        $this->expectException(InvalidPasswordFormatException::class);

        $wrongPasswordClient = RegisterClientMother::register(
            plainPassword: '@Prueba',
            repeatedPlainPassword: '@Prueba'
        );
    }

    /**
     * @test
     */
    public function shouldThrowPasswordNotEqualsException()
    {
        $this->expectException(PasswordNotEqualsException::class);

        $notEqualsPasswordClient = RegisterClientMother::register(
            plainPassword: '@Prueba123',
            repeatedPlainPassword: '@Prueba123-not-match'
        );
    }

    protected function clientFinderByEmail(): ClientFinderByEmail|MockObject
    {
        $clientFinderByEmail = $this->getMockBuilder(ClientFinderByEmail::class)->disableOriginalConstructor()->getMock();

        return $this->clientFinderByEmail ??= $clientFinderByEmail;
    }
}
