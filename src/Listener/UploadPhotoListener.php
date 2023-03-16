<?php


namespace App\Listener;


use App\Component\LocalUploader;
use App\Entity\Photo;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UploadPhotoListener
{
    public const FOLDER = 'images';
    private $uploader;

    public function __construct(LocalUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function uploadPhoto(Photo $photo, LifecycleEventArgs $event)
    {
        $this->uploader->upload($photo, self::FOLDER);
    }
}