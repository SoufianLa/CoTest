<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class AuthenticationDTO
{
    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false,
     *     groups={"signup"}
     * )
     */
    private $firstName;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 25,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false,
     *     groups={"signup"}
     * )
     */
    private $lastName;


    /**
     * @Assert\NotBlank(groups={"signup", "login"})
     */
    private $email;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false,
     *     groups={"signup", "login"}
     * )
     * @Assert\Regex("/\d/", groups={"signup", "login"}, message="The password must contain at least one number.")
     */
    private $password;


    /**
     * @Assert\Count(
     *      min = 1,
     *      max = 4,
     *      minMessage = "You must specify at least one image",
     *      maxMessage = "You cannot specify more than {{ limit }} images",
     *      groups={"signup"}
     * )
     */
    private $photos;

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

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos ?? [];
    }

    /**
     * @param mixed $photos
     */
    public function setPhotos($photos): void
    {
        $this->photos = $photos;
    }
}