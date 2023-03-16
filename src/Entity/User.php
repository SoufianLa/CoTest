<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Serializer\ExclusionPolicy("ALL")
 */
class User
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose
     * @Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Serializer\Expose
     * @Groups({"user"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=25)
     * @Serializer\Expose
     * @Groups({"user"})
     */
    private $lastName;


    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Serializer\Expose
     * @Groups({"user"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active=true;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar="avatar.jpg";

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="user", orphanRemoval=true)
     */
    private $photos;

    /**
     * @Serializer\Expose
     * @Groups({"user"})
     */
    private $accessToken;

    /**
     * @Serializer\Expose
     * @Groups({"user"})
     */
    private $refreshToken;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("fullName")
     *
     */
    public function getFullName()
    {
        return $this->firstName." ".$this->lastName;
    }

    public function setFullName($firstName, $lastName): void
    {
        $this->fullName = $firstName." ".$lastName;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhotos(Photo $photos): self
    {
        if (!$this->photos->contains($photos)) {
            $this->photos[] = $photos;
            $photos->setUser($this);
        }

        return $this;
    }

    public function removePhotos(Photo $photos): self
    {
        if ($this->photos->removeElement($photos)) {
            // set the owning side to null (unless already changed)
            if ($photos->getUser() === $this) {
                $photos->setUser(null);
            }
        }

        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function setRefreshToken($refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }


}
