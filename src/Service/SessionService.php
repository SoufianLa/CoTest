<?php

namespace App\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Hydrator\SessionHydrator;
use App\Security\TokenGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class SessionService
{
    private $em;
    private $tokenGenerator;
    private $sessionHydartor;
    public function __construct( EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator, SessionHydrator $sessionHydrator)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $entityManager;
        $this->sessionHydartor = $sessionHydrator;
    }

    public function generateSession(User $user): User
    {
        $tokens = $this->generateTokens($user);
        $session = $user->getSession() ?? new Session();
        $refreshNumber = $session->getRefreshNumber() ?? 0;
        $this->sessionHydartor->hydrateUserFromParams($session, $tokens['accessToken'], $tokens['refreshToken'], $refreshNumber, $user);
        $user->setSession($session);
        $this->em->persist($session);
        $this->em->flush();
        return $user;
    }
    private function generateTokens(User $user): array
    {
        $accessToken = $this->tokenGenerator->generateToken(["id" => $user->getId(), "email" => $user->getEmail()], TokenGeneratorInterface::TYPE_ACCESS);
        $refreshToken = $this->tokenGenerator->generateToken(["id" => $user->getId(), "email" => $user->getEmail()], TokenGeneratorInterface::TYPE_REFRESH);
        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
        ];
    }
}