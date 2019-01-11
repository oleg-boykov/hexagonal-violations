<?php

namespace App\Presentation\Security;

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTAuthenticator extends AbstractGuardAuthenticator
{
    const AUTH_HEADER = 'Authorization';

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var array
     */
    private $algorithms;

    /**
     * JWTAuthenticator constructor.
     * @param string $secretKey
     * @param array $algorithms
     */
    public function __construct(string $secretKey, array $algorithms)
    {
        $this->secretKey = $secretKey;
        $this->algorithms = $algorithms;
    }

    /**
     * @inheritdoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        if ($authException) {
            return new JsonResponse(['message' => $authException->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse(['message' => 'Authentication Required'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritdoc
     */
    public function supports(Request $request)
    {
        return $request->headers->has(self::AUTH_HEADER);
    }

    /**
     * @inheritdoc
     */
    public function getCredentials(Request $request)
    {
        $header = $request->headers->get(self::AUTH_HEADER);
        $token = str_replace('Bearer ', '', $header);
        if (!$token) {
            return null;
        }

        return $token;
    }

    /**
     * @inheritdoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $token = (array) JWT::decode($credentials, $this->secretKey, $this->algorithms);
        } catch (\Exception $e) {
            throw new AuthenticationException('Authentication failed: '.$e->getMessage());
        }
        if (isset($token['uid']) && is_numeric($token['uid']) && $token['uid'] > 0) {
            return new User($token['uid']);
        }

        throw new AuthenticationException('Authentication failed: Wrong uid');
    }

    /**
     * @inheritdoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    /**
     * @inheritdoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
