<?php


namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class Authenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $jwt;

    public function __construct(EntityManagerInterface $em, Jwt $jwt)
    {
        $this->em = $em;
        $this->jwt = $jwt;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = ['message' => 'Authentication Required'];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return true;
    }
    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $token=$this->jwt->extractToken($request, $this->jwt::TYPE_ACCESS);
        return ['token' => $token];
    }
    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $data = $this->jwt->decodeToken($credentials['token']);
        if (is_null($data)) {
            return null;
        }
        $username = isset($data['email']) ? $data['email'] : null;
        return $userProvider->loadUserByUsername($username);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = ['message' => 'Token expired or not valid'];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }

}