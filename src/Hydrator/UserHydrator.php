<?php

namespace App\Hydrator;

use App\DTO\AuthenticationDTO;
use App\Entity\Photo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserHydrator
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function hydrateUserFromDTO(User $user, AuthenticationDTO $DTO): void{
        $user->setFirstName($DTO->getFirstName());
        $user->setLastName($DTO->getLastName());
        $user->setEmail($DTO->getEmail());
        $user->setPassword($DTO->getPassword());
        foreach ($DTO->getPhotos() as $photoData) {
            $photo = new Photo($photoData);
            $user->addPhotos($photo);
            $this->em->persist($photo);
        }
    }

}