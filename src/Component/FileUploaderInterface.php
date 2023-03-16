<?php


namespace App\Component;



interface FileUploaderInterface
{
    public function upload(FileInterface $file, string $folderName): void;

    public function remove(string $path): bool;

}