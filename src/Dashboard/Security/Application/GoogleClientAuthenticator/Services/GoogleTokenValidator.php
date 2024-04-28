<?php

namespace App\Dashboard\Security\Application\GoogleClientAuthenticator\Services;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class GoogleTokenValidator
{
    private \Google_Client $client;

    public function __construct(
        #[Autowire(param: 'google_client_id')]
        private string $google_client_id
    ) {
        $this->client = new \Google_Client([
            'client_id' => $google_client_id,
        ]);  // Specify the CLIENT_ID of the app that accesses the backend
    }

    public function __invoke(string $id_token): array
    {
        try {
            $payload = $this->client->verifyIdToken($id_token);
        } catch (\UnexpectedValueException $e) {
            throw new CustomUserMessageAuthenticationException('Token validation wrong');
        }

        $this->checkPayload($payload);

        return $payload;
    }

    private function checkPayload(array|false $payload): void
    {
        if (!$payload) {
            throw new CustomUserMessageAuthenticationException('Token id verification failed');
        }
    }
}
