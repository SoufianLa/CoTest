<?php


namespace App\Service;

use App\DTO\AuthenticationDTO;
use App\Entity\Photo;
use App\Entity\User;
use App\Exception\ApiException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationService
{
    private $em;
    private $sessionService;
    public function __construct(EntityManagerInterface $entityManager, SessionService $sessionService)
    {
        $this->em = $entityManager;
        $this->sessionService = $sessionService;
    }

    public function signUp(AuthenticationDTO $DTO): ?User
    {
        try {
            $user = new User();
            $user->setFirstName($DTO->getFirstName());
            $user->setLastName($DTO->getLastName());
            $user->setEmail($DTO->getEmail());
            $user->setPassword($DTO->getPassword());
            foreach ($DTO->getPhotos() as $ph) {
                $photo = new Photo($ph);
                $user->addPhotos($photo);
                $this->em->persist($photo);
            }
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }catch (UniqueConstraintViolationException $e) {
                throw new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, "CONSTRAINS_VIOLATION");
        }catch (\Exception $ex) {
                throw new ApiException(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage());
        }
    }

    public  function login(AuthenticationDTO $DTO): ?User
    {
        /* @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $DTO->getEmail()]);

        if (!isset($user)) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, "LOGIN_FAILED");
        }

        if (!password_verify($DTO->getPassword(), $user->getPassword()))
            throw new ApiException(Response::HTTP_UNAUTHORIZED, "UNAUTHORIZED");
        return $this->sessionService->generateSession($user);
    }

}