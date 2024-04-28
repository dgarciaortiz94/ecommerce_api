<?php

namespace App\Dashboard\Client\Infrastructure\TokenGenerator;

use App\Dashboard\Client\Domain\Agregates\AuthenticatedClient;
use App\Dashboard\Client\Domain\TokenGenerator\ITokenGenerator;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CustomJwtGenerator implements ITokenGenerator
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/config')]
        private string $configDir,
    ) {
    }

    public function generateToken(AuthenticatedClient $client): string
    {
        $timestamp = time();

        $public_key_content = file_get_contents($this->configDir.'/secret/jwt/public_key.pem');
        $public_key = openssl_pkey_get_details(openssl_pkey_get_public($public_key_content))['key'];

        $payload = [
            'aud' => 'http://localhost',
            'exp' => $timestamp + (12 * 60 * 60), // 12h
            'iat' => $timestamp,
            'iss' => (!empty($_SERVER['HTTPS']) ? 'https' : 'http').'://',
            // 'nbf' => $timestamp + 476,
            'name' => $client->name(),
            'surname' => $client->surname(),
            'secondSurname' => $client->secondSurname(),
            'email' => $client->email(),
            'roles' => $client->getRoles(),
        ];

        $jwt = JWT::encode($payload, $public_key, 'HS256');

        return $jwt;
    }
}
