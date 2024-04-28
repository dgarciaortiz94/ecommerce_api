<?php

namespace App\Dashboard\Client\Application\SignIn\SignInWithGoogle;

use App\Dashboard\Client\Application\Register\RegisterClientWithGoogle\RegisterClientWithGoogleCommand;
use App\Dashboard\Client\Application\SignIn\Shared\SignInProfileResponse;
use App\Dashboard\Client\Application\SignIn\Shared\SignInResponse;
use App\Dashboard\Client\Application\SignIn\SignInWithGoogle\Services\SignInWithGoogleAuthenticator;
use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\Services\ClientFinderByEmail;
use App\Dashboard\Security\Application\GoogleClientAuthenticator\Services\GoogleTokenValidator;
use Google\Service\PeopleService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class SignInWithGoogleCase
{
    public function __construct(
        private ClientFinderByEmail $clientFinderByEmail,
        private SignInWithGoogleAuthenticator $signInWithGoogleAuthenticator,
        private GoogleTokenValidator $tokenValidator,
        private MessageBusInterface $bus
    ) {
    }

    public function __invoke(string $code, string $requestedWithHeader): SignInResponse
    {
        $googleOAuth2Response = $this->signInWithGoogleAuthenticator->__invoke($code, $requestedWithHeader);

        $id_token = $googleOAuth2Response['id_token'];
        $access_token = $googleOAuth2Response['access_token'];
        $refresh_token = $googleOAuth2Response['refresh_token'];

        $payload = $this->tokenValidator->__invoke($id_token);

        try {
            $client = $this->findLoggedInClient($payload['email']);
        } catch (NotFoundHttpException $e) {
            $this->registerNonExistentClient($payload['email'], $access_token);

            $client = $this->findLoggedInClient($payload['email']);
        }

        return new SignInResponse(
            new SignInProfileResponse(
                $client->getUserIdentifier(),
                $client->name(),
                $client->surname(),
                $client->email(),
                $client->getRoles(),
                $client->secondSurname(),
            ),
            $id_token
        );
    }

    private function findLoggedInClient(string $clientEmail): AuthenticatedClient
    {
        return $this->clientFinderByEmail->__invoke($clientEmail);
    }

    private function registerNonExistentClient(
        string $clientEmail,
        string $access_token
    ): Envelope {
        try {
            $client = new \Google_Client();
            $client->setAccessToken($access_token);

            $peopleService = new PeopleService($client);
            $clientInfo = $peopleService->people->get('people/me', [
                'personFields' => 'names,emailAddresses,photos,birthdays',
            ]);

            return $this->bus->dispatch(new RegisterClientWithGoogleCommand(
                $clientInfo->getNames()[0]->getGivenName(),
                $clientInfo->getNames()[0]->getFamilyName(),
                $clientEmail,
            ));
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
