<?php

namespace App\Dashboard\Security\Application\ApplicationClientAuthenticator\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class ApplicationTokenValidator
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/config')]
        private string $configDir,
    ) {
    }

    public function __invoke(string $id_token): \stdClass
    {
        $public_key = openssl_pkey_get_public('file://'.$this->configDir.'/secret/jwt/public_key.pem');

        try {
            $payload = JWT::decode(
                $id_token,
                new Key(openssl_pkey_get_details($public_key)['key'], 'HS256')
            );
        } catch (\UnexpectedValueException $e) {
            throw new CustomUserMessageAuthenticationException('Token validation wrong');
        }

        $this->checkPayload($payload);

        return $payload;
    }

    private function checkPayload(\stdClass $payload): void
    {
        if (!$payload) {
            throw new CustomUserMessageAuthenticationException('Token id verification failed');
        }
    }
}
