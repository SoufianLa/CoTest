<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class AuthenticationDTO
{
    /**
     * @Assert\NotBlank(groups={"signup"})
     */
    private $firstName;

    /**
     * @Assert\NotBlank(groups={"signup"})
     */
    private $lastName;


    /**
     * @Assert\NotBlank(groups={"signup"})
     */
    private $email;

    /**
     * @Assert\NotBlank(groups={"signup"})
     */
    private $password;

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }






}