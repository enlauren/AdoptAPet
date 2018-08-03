<?php

namespace App\ImageBundle\Service\Handler\ImageType;

use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use Doctrine\ORM\ORMException;
use Exception;
use App\ImageBundle\Entity\Repository\ImageRepository;
use App\ImageBundle\Service\Command\UploadImageCommand;
use Intervention\Image\Image;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use App\ImageBundle\Entity\Image as ImageEntity;

class OptimisedImageType implements ImageTypeInterface
{
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ImageRepository $imageRepository, ClassifiedRepository $classifiedRepository, LoggerInterface $logger)
    {
        $this->imageRepository      = $imageRepository;
        $this->classifiedRepository = $classifiedRepository;
        $this->logger               = $logger;
    }

    public function getName(): string
    {
        return bin2hex(random_bytes(3));
    }

    public function getTransformed(Image $image)
    {
        $image->orientate();
        $image->fit(1024, null, function ($constraint) {
            $constraint->upsize();
        });

        return $image->encode('jpg', 50)->getEncoded();
    }

    /**
     * @throws Exception
     */
    public function save(UploadImageCommand $command, string $path, Classified $classified)
    {
        if ($command->getImageId()) {
            $imageEntity = $this->imageRepository->find($command->getImageId());
        }

        if (!$imageEntity) {
            $imageEntity = new ImageEntity();
        }

        $imageEntity->setCdnPath($path);
        $imageEntity->setClassified($classified);

        $classified->getImages()->add($imageEntity);

        try {
            $this->classifiedRepository->save($classified, true);
        } catch (ORMException $exception) {
            $this->logger->log(LogLevel::ERROR, 'Unable to save classified after main image was uploaded. ', [
                'classified' => json_encode($classified),
                'image'      => json_encode($imageEntity),
            ]);

            throw new Exception($exception->getMessage());
        }
    }

    public function getDirectoryPrefix(): string
    {
        return '';
    }
}
