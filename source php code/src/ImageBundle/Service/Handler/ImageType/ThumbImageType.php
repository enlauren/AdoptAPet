<?php

namespace App\ImageBundle\Service\Handler\ImageType;

use App\AppBundle\Entity\Classified;
use App\ImageBundle\Entity\Repository\ImageRepository;
use App\ImageBundle\Service\Command\UploadImageCommand;
use Intervention\Image\Image;
use App\ImageBundle\Entity\Image as ImageEntity;

class ThumbImageType implements ImageTypeInterface
{
    /**
     * @var int
     */
    private $width = 252;

    /**
     * @var int
     */
    private $height = 189;

    /**
     * @var ImageRepository
     */
    private $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function getName(): string
    {
        return 'thumb-' . bin2hex(random_bytes(3));
    }

    public function getTransformed(Image $image)
    {
        $image->fit($this->width, $this->height);

        return $image->encode('jpg', 50)->getEncoded();
    }

    public function save(UploadImageCommand $command, string $path, Classified $classified)
    {
        /** @var ImageEntity $imageEntity */
        $imageEntity = $this->imageRepository->find($command->getImageId());
        $imageEntity->setCloudThumb($path);
        $this->imageRepository->save($imageEntity);
    }

    public function getDirectoryPrefix(): string
    {
        return 'thumbs/';
    }
}
