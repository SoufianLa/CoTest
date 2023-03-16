<?php


namespace App\Service;

use App\DTO\AuthenticationDTO;
use App\Entity\User;
use App\Exception\ApiException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationService
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function signUp(AuthenticationDTO $DTO): ?User
    {
        try {
            $user = new User();
            $user->setFirstName($DTO->getFirstName());
            $user->setLastName($DTO->getLastName());
            $user->setEmail($DTO->getEmail());
            $user->setPassword($DTO->getPassword());
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }catch (UniqueConstraintViolationException $e) {
                throw new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, "CONSTRAINS_VIOLATION");
        }catch (\Exception $ex) {
                throw new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage());
        }
    }

}