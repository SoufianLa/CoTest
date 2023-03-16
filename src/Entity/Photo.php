<?php

namespace App\Entity;

use App\Component\File as  FileComponent;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo extends FileComponent
{
    use TimestampableTrait;

    public function __construct(File $file)
    {
        $this->file = $file;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $path;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->getUri()."/".$this->path;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }


    public function getPath(): string
    {
        return $this->path;
    }


    public function setPath( ?string $path): void
    {
        $this->path = $path;
    }

}
