<?php

namespace App\Tests\Dashboard\Client\Application\SignIn\SignInWithApplication;

use App\Dashboard\Client\Application\SignIn\Exception\SignInWrongProviderException;
use App\Dashboard\Client\Application\SignIn\Shared\SignInResponse;
use App\Dashboard\Client\Application\SignIn\SignInWithApplication\Services\ClientPasswordChecker;
use App\Dashboard\Client\Application\SignIn\SignInWithApplication\SignInWithApplicationCase;
use App\Dashboard\Client\Application\SignIn\SignInWithApplication\Utils\SignInWithApplicationCredentials;
use App\Dashboard\Client\Domain\Services\ClientFinderByEmail;
use App\Dashboard\Client\Infrastructure\TokenGenerator\CustomJwtGenerator;
use App\Tests\Dashboard\Client\Application\AbstractClientApplicationMock;
use App\Tests\Dashboard\Client\Domain\RegisterClientMother;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;

class SignInWithApplicationTest extends AbstractClientApplicationMock
{
    protected ClientPasswordChecker|MockObject|null $clientPasswordChecker = null;
    protected CustomJwtGenerator|MockObject|null $tokenGenerator = null;

    /**
     * @test
     */
    public function shouldAuthenticateClientSuccessfully()
    {
        $clientPasswordChecker = $this->clientPasswordChecker();

        $clientPasswordChecker
            ->expects($this->once())
            ->method('__invoke')
            ->willReturn(true);

        $tokenGenerator = $this->tokenGenerator();

        $tokenGenerator
            ->expects($this->once())
            ->method('generateToken')
            ->willReturn('
                eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9
                .eyJhdWQiOiJodHRwOi8vbG9jYWxob3N0IiwiZXhwIjozMDAw
                MDAsImlhdCI6MTI3MzQ3NDM5MzQsImlzcyI6Imh0dHA6Ly9le
                GFtcGxlLmNvbSIsIm5iZiI6MTI3MzQ3NDUwMDAsIm5hbWUiOi
                JEaWVnbyIsInN1cm5hbWUiOiJHYXJjw61hIiwic2Vjb25kU3V
                ybmFtZSI6Ik9ydGl6IiwiZW1haWwiOiJtYWlsQG1haWwuY29t
                Iiwicm9sZXMiOlsiUk9MRV9VU0VSIl19
                .9kOZwl5LfMHQtc4bmH1Ts1cgPs6Rg7Lcza0yjFWVwWo
            ');

        $repository = $this->repository();

        $repository
            ->expects($this->once())
            ->method('searchByCriteria')
            ->willReturn([RegisterClientMother::register()]);

        $authenticateClientCase = new SignInWithApplicationCase(new ClientFinderByEmail($repository), $clientPasswordChecker, $tokenGenerator);

        $registerClientResponse = $authenticateClientCase->__invoke(SignInWithApplicationCredentials::create('mail@mail.com', '@Prueba123'));

        $this->assertEquals(SignInResponse::class, $registerClientResponse::class);
    }

    /**
     * @test
     */
    public function shouldThrowNotFoundHttpException()
    {
        $clientPasswordChecker = $this->clientPasswordChecker();

        $repository = $this->repository();

        $repository
            ->expects($this->once())
            ->method('searchByCriteria')
            ->willReturn([]);

        $this->expectException(NotFoundHttpException::class);

        $signInWithApplicationCase = new SignInWithApplicationCase(new ClientFinderByEmail($this->repository()), $clientPasswordChecker, $this->tokenGenerator());

        $signInWithApplicationCase->__invoke(SignInWithApplicationCredentials::create('no_existent_client@mail.com', '@Prueba123'));
    }

    /**
     * @test
     */
    public function shouldThrowInvalidProviderException()
    {
        $clientPasswordChecker = $this->clientPasswordChecker();

        $repository = $this->repository();

        $repository
            ->expects($this->once())
            ->method('searchByCriteria')
            ->willReturn([RegisterClientMother::registerWithGoogle(
                email: 'dgarciaortiz94@gmail.com'
            )]);

        $signInWithApplicationCase = new SignInWithApplicationCase(new ClientFinderByEmail($this->repository()), $clientPasswordChecker, $this->tokenGenerator());

        $this->expectException(SignInWrongProviderException::class);

        $signInWithApplicationCase->__invoke(SignInWithApplicationCredentials::create('dgarciaortiz94@gmail.com', '@Prueba123'));
    }

    /**
     * @test
     */
    public function shouldThrowWrongPasswordException()
    {
        $clientPasswordChecker = $this->clientPasswordChecker();

        $clientPasswordChecker
            ->expects($this->once())
            ->method('__invoke')
            ->willThrowException(new InvalidPasswordException('Password is not correct'));

        $repository = $this->repository();

        $repository
            ->expects($this->once())
            ->method('searchByCriteria')
            ->willReturn([RegisterClientMother::register()]);

        $signInWithApplicationCase = new SignInWithApplicationCase(new ClientFinderByEmail($this->repository()), $clientPasswordChecker, $this->tokenGenerator());

        $this->expectException(InvalidPasswordException::class);

        $signInWithApplicationCase->__invoke(SignInWithApplicationCredentials::create('mail@mail.com', '@Prueba123_wrong'));
    }

    protected function clientPasswordChecker(): ClientPasswordChecker|MockObject
    {
        $clientPasswordChecker = $this->getMockBuilder(ClientPasswordChecker::class)->disableOriginalConstructor()->getMock();

        return $this->clientPasswordChecker ??= $clientPasswordChecker;
    }

    protected function tokenGenerator(): CustomJwtGenerator|MockObject
    {
        $tokenGenerator = $this->getMockBuilder(CustomJwtGenerator::class)->disableOriginalConstructor()->getMock();

        return $this->tokenGenerator ??= $tokenGenerator;
    }
}
