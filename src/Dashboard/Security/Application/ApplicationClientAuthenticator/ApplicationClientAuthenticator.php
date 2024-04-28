<?php

namespace App\Dashboard\Security\Application\ApplicationClientAuthenticator;

use App\Dashboard\Client\Domain\Agregates\ClientProvider;
use App\Dashboard\Security\Application\ApplicationClientAuthenticator\Services\ApplicationTokenValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApplicationClientAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private ApplicationTokenValidator $tokenValidator
    ) {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        $provider = $request->headers->get('X-Auth-Provider') ?? null;

        return ClientProvider::APPLICATION === $provider;
    }

    public function authenticate(Request $request): Passport
    {
        $id_token = $this->getBearerToken($request);

        if (null === $id_token) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        // implement your own logic to get the user identifier from `$apiToken`
        // e.g. by looking up a user in the database using its API key
        // $userIdentifier = /** ... */;

        $payload = $this->tokenValidator->__invoke($id_token);

        return new SelfValidatingPassport(new UserBadge($payload->email));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function getBearerToken(Request $request): ?string
    {
        $authorizationHeader = $request->headers->get('Authorization');

        // skip beyond "Bearer "
        return (!empty($id_token = substr($authorizationHeader, 7))) ? $id_token : null;
    }
}
