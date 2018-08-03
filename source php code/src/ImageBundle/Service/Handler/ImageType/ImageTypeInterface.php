<?php

namespace App\ImageBundle\Service\Handler\ImageType;

use App\AppBundle\Entity\Classified;
use App\ImageBundle\Service\Command\UploadImageCommand;
use Intervention\Image\Image;

interface ImageTypeInterface
{
    public function getName(): string;
    public function getTransformed(Image $image);
    public function save(UploadImageCommand $command, string $path, Classified $classified);
    public function getDirectoryPrefix(): string;
}
