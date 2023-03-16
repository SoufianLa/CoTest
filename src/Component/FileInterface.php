<?php


namespace App\Component;

interface FileInterface
{
    /**
     * @return string
     */
    public function getName(): ?string;

    public function setName(?string $type);

    public function getFile(): ?\SplFileInfo;

    public function setFile(?\SplFileInfo $file): void;

    public function hasFile(): bool;

    public function getPath();

    public function setPath(?string $path);
}