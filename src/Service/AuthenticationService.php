<?php


namespace App\Service;

use App\DTO\AuthenticationDTO;
use App\Entity\User;
use App\Exception\ApiException;
use App\Security\Jwt;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationService
{
    private $em;
    private $jwt;
    public function __construct(EntityManagerInterface $entityManager, Jwt $jwt)
    {
        $this->em = $entityManager;
        $this->jwt = $jwt;
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

    public  function login(AuthenticationDTO $DTO): ?User
    {
        /* @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(["email" => $DTO->getEmail()]);

        if (!isset($user)) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, "LOGIN_FAILED");
        }

        if (!password_verify($DTO->getPassword(), $user->getPassword()))
            throw new ApiException(Response::HTTP_UNAUTHORIZED, "UNAUTHORIZED");
        $this->setTokens($user);
        return $user;
    }

    public function setTokens(User $user): void
    {
        $accessToken = $this->jwt->generateToken(["id" => $user->getId(), "email" => $user->getEmail()], Jwt::TYPE_ACCESS);
        $refreshToken = $this->jwt->generateToken(["id" => $user->getId(), "email" => $user->getEmail()], Jwt::TYPE_REFRESH);
        $user->setAccessToken($accessToken);
        $user->setRefreshToken($refreshToken);

    }

}