<?php


namespace App\Component;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Serializer\ExclusionPolicy("ALL")
 */
abstract class File implements FileInterface
{
    protected $id;

    protected $name;

    /** @var \SplFileInfo|null */
    protected $file;

    protected $path;

    /** @var object|null */
    protected $user;

    public function getId()
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
    }

    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }

    public function setFile(?\SplFileInfo $file): void
    {
        $this->file = $file;
    }

    public function hasFile(): bool
    {
        return null !== $this->file;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath(?string $path)
    {
        $this->path = $path;
    }

    public function hasPath(): bool
    {
        return null !== $this->path;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUri()
    {
        return $_ENV['BASE_IMAGE_URL'];
    }
}
