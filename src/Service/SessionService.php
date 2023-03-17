<?php

namespace App\Service;

use App\Entity\Session;
use App\Entity\User;
use App\Security\TokenGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class SessionService
{
    private $em;
    private $tokenGenerator;
    public function __construct( EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $entityManager;
    }

    public function generateSession(User $user): User
    {
        $session = $this->makeTokens($user);
        $user->setSession($session);
        $this->em->persist($session);
        $this->em->flush();
        return $user;
    }

    private function makeTokens(User $user): Session
    {
        $tokens = $this->generateTokens($user);
        $session = $user->getSession() ?? new Session();;
        $session->setAccessToken($tokens['accessToken']);
        $session->setRefreshToken($tokens['refreshToken']);
        $session->setRefreshNumber($session->getRefreshNumber() ?? 0);
        $session->setUser($user);
        $this->em->persist($session);
        $this->em->flush();
        return $session;

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