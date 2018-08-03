<?php
declare(strict_types=1);

namespace App\ImageBundle\Service\Handler;

use App\ImageBundle\Entity\Image;
use App\ImageBundle\Entity\Repository\ImageRepository;
use App\ImageBundle\Service\Command\CreateThumbImageCommand;
use App\ImageBundle\Service\ImagePathBuilder;
use Intervention\Image\ImageManager;
use Psr\Log\LoggerInterface;

class CreateImageThumbHandler
{
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImagePathBuilder
     */
    private $imagePathBuilder;

    /**
     * @param ImageRepository  $imageRepository
     * @param ImagePathBuilder $imagePathBuilder
     * @param LoggerInterface  $logger
     */
    public function __construct(ImageRepository $imageRepository, ImagePathBuilder $imagePathBuilder, LoggerInterface $logger)
    {
        $this->imageRepository  = $imageRepository;
        $this->imagePathBuilder = $imagePathBuilder;
        $this->logger           = $logger;
    }

    /**
     * @param CreateThumbImageCommand $createThumbImageCommand
     * @return bool
     */
    public function handle(CreateThumbImageCommand $createThumbImageCommand)
    {
        /** @var Image $imageEntity */
        $imageEntity = $this->imageRepository->find($createThumbImageCommand->getImageId());

        if (!$imageEntity) {
            return false;
        }

        $classifiedId = $imageEntity->getClassified()->getId();
        $fileName     = $imageEntity->getFile();


        $imageManager = new ImageManager(['driver' => 'imagick']);
        $image        = $imageManager->make($this->imagePathBuilder->get($classifiedId, $fileName));
        $image->save($this->imagePathBuilder->getThumb($classifiedId, $fileName), 50);

        $imageEntity->setThumb($fileName);
        $this->imageRepository->save($imageEntity);

        return true;
    }
}
