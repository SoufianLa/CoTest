<?php

namespace App\Hydrator;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;

class SessionHydrator
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function hydrateUserFromParams(Session $session, ...$params)
    {
        $session->setAccessToken($params[0]);
        $session->setRefreshToken($params[1]);
        $session->setRefreshNumber($params[2]);
        $session->setUser($params[3]);
        $this->em->persist($session);
    }

}