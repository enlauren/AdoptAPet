<?php
declare(strict_types = 1);

namespace App\ImageBundle\Service;

use AppBundle\Entity\Classified;
use App\ImageBundle\Entity\Image;

class ImageFactory
{
    /**
     * @param $path
     *
     * @return Image
     */
    public static function create(Classified $classified, string $path): Image
    {
        $image = new Image();
        $image->setFile($path);
        $image->setClassified($classified);

        return $image;
    }
}
