<?php
declare(strict_types=1);

namespace App\ImageBundle\Service;

use App\ImageBundle\Entity\Image;
use Symfony\Component\Routing\RouterInterface;

class ImageExtension extends \Twig_Extension
{
    /**
     * @var ImagePathBuilder
     */
    private $imagePathBuilder;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param ImagePathBuilder $imagePathBuilder
     */
    public function __construct(ImagePathBuilder $imagePathBuilder, RouterInterface $router)
    {
        $this->imagePathBuilder = $imagePathBuilder;
        $this->router           = $router;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('imageLink', [$this, 'getImageLink']),
            new \Twig_Filter('thumb', [$this, 'getThumbLink']),
        ];
    }

    /**
     * @param Image $image
     *
     * @return string
     */
    public function getThumbLink(Image $image): string
    {
        if ($image->getThumb()) {
            // if classified is part of the first type of entity.
            $classifiedId = $image->getClassified()->getOldId() ? $image->getClassified()->getOldId() : $classifiedId = $image->getClassified()->getId();

            return $this->router->generate('get.image.thumb', [
                'id'   => $classifiedId,
                'file' => $image->getThumb()
            ]);
        }


        // if thumb doesn't exists fallback on big image
        if ($image->getFile()) {
            return $this->getImageLink($image);
        }

        return '';
    }

    /**
     * @param Image $image
     * @return string
     */
    public function getImageLink(Image $image)
    {
        $classifiedId = $image->getClassified()->getOldId() ? $image->getClassified()->getOldId() : $image->getClassified()->getId();

        return $this->router->generate('get.image', [
            'id'   => $classifiedId,
            'file' => $image->getFile()
        ]);
    }

    public function getName()
    {
        return 'app_image_extension';
    }
}
