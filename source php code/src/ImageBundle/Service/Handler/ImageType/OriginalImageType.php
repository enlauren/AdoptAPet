<?php

namespace App\ImageBundle\Service\Handler\ImageType;

use App\AppBundle\Entity\Classified;
use App\ImageBundle\Service\Command\UploadImageCommand;
use Intervention\Image\Image;

class OriginalImageType implements ImageTypeInterface
{
    public function getName(): string
    {
        return 'original-' . bin2hex(random_bytes(3));
    }

    public function getTransformed(Image $image)
    {
        return $image->encode('jpg', 100)->getEncoded();
    }

    public function save(UploadImageCommand $command, string $path, Classified $classified)
    {
        return true;
    }

    public function getDirectoryPrefix(): string
    {
        return '';
    }
}
