<?php
declare(strict_types = 1);

namespace App\ImageBundle\Service\Command;

class CreateThumbImageCommand
{
    /**
     * @var int
     */
    private $imageId;

    /**
     * @return mixed
     */
    public function getImageId(): int
    {
        return $this->imageId;
    }

    /**
     * @param mixed $imageId
     */
    public function setImageId(int $imageId)
    {
        $this->imageId = $imageId;
    }
}

