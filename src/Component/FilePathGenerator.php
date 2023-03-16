<?php


namespace App\Component;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePathGenerator implements FilePathGeneratorInterface
{
    public function generate(FileInterface $file, string $folderName): string
    {
        /** @var UploadedFile $splFile */
        $splFile = $file->getFile();
        return $folderName."/".(md5("1" . microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"])).'.' . $splFile->guessExtension();
    }

}