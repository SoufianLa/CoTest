<?php


namespace App\Service;

use App\DTO\AuthenticationDTO;
use App\Entity\Photo;
use App\Entity\User;
use App\Exception\ApiException;
use App\Hydrator\UserHydrator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationService
{
    private $em;
    private $sessionService;
    private $userHydrator;
    public function __construct(EntityManagerInterface $entityManager, SessionService $sessionService, UserHydrator $userHydrator)
    {
        $this->em = $entityManager;
        $this->sessionService = $sessionService;
        $this->userHydrator = $userHydrator;
    }

    public function signUp(AuthenticationDTO $DTO): ?User
    {
        try {
            $user = new User();
            $this->userHydrator->hydrateUserFromDTO($user, $DTO);
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