<?php

namespace App\Component;

interface FilePathGeneratorInterface
{
public function generate(FileInterface $file, string $folderName): string;
}