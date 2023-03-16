<?php


namespace App\Component;


use Gaufrette\Filesystem;
use Gaufrette\FilesystemInterface;
use Webmozart\Assert\Assert;
use Gaufrette\Adapter\Local as LocalAdapter;

class LocalUploader implements FileUploaderInterface
{
    protected $filesystem;
    /** @var FilePathGeneratorInterface */
    protected $filePathGenerator;

    public function __construct(FilePathGeneratorInterface $filePathGenerator)
    {
        $this->filePathGenerator = $filePathGenerator;
    }


    public function upload(FileInterface $file, string $folderName): void
    {
        $adapter = new LocalAdapter($folderName);
        $this->filesystem = new Filesystem($adapter);
        if (!$file->hasFile()) {
            return;
        }
        if (null !== $file->getPath()) {
            $this->remove($file->getPath());
        }
        $path = $this->filePathGenerator->generate($file, $folderName);
        $file->setPath(basename($folderName)."/".basename($path));
        $file->setName("p_".$_SERVER["REQUEST_TIME_FLOAT"]);
        $this->filesystem->write(basename($path), file_get_contents($file->getFile()->getPathname()));

    }

    /**
     * @param string $path
     * @return bool
     */
    public function remove(string $path): bool
    {
        return $this->filesystem->delete($path);
    }




}