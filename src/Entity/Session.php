<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Serializer\ExclusionPolicy("ALL")
 */
class Session
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     * @Groups({"Auth"})
     */
    private $accessToken;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose
     * @Groups({"Auth"})
     */
    private $refreshToken;

    /**
     * @ORM\Column(type="integer")
     */
    private $refreshNumber;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="session")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getRefreshNumber(): ?int
    {
        return $this->refreshNumber;
    }

    public function setRefreshNumber(int $refreshNumber): self
    {
        $this->refreshNumber = $refreshNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}


