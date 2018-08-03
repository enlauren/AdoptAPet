<?php
declare(strict_types=1);

namespace App\ImageBundle\Service\Command;

class UploadImageCommand
{
    /** @var int */
    private $classifiedId;

    /** @var int */
    private $imageId;

    /** @var string */
    private $content;

    /** @var string */
    private $extension;

    public function __construct(int $classifiedId, int $imageId = null, string $content, string $extension)
    {
        $this->classifiedId = $classifiedId;
        $this->imageId      = $imageId;
        $this->content      = $content;
        $this->extension    = $extension;
    }

    public function getClassifiedId(): int
    {
        return $this->classifiedId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getImageId(): ?int
    {
        return $this->imageId;
    }
}

