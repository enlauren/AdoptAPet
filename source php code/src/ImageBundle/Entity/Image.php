<?php
declare(strict_types = 1);

namespace App\ImageBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use App\AppBundle\Entity\Classified;

class Image
{
    use Identifiable;

    /**
     * @var Classified
     */
    private $classified;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $thumb = '';

    /**
     * @var string
     */
    private $cloudThumb = '';

    /**
     * @var string
     */
    private $cdnPath;

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     *
     * @return $this
     */
    public function setFile(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Classified
     */
    public function getClassified() : Classified
    {
        return $this->classified;
    }

    /**
     * @param Classified $classified
     *
     * @return $this
     */
    public function setClassified(Classified $classified)
    {
        $this->classified = $classified;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumb(): string
    {
        return $this->thumb;
    }

    /**
     * @param string $thumb
     */
    public function setThumb(string $thumb)
    {
        $this->thumb = $thumb;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getFile();
    }

    /**
     * @return string
     */
    public function getCdnPath(): string
    {
        return $this->cdnPath;
    }

    /**
     * @param string $cdnPath
     */
    public function setCdnPath(string $cdnPath): void
    {
        $this->cdnPath = $cdnPath;
    }

    /**
     * @return string
     */
    public function getCloudThumb(): string
    {
        return $this->cloudThumb;
    }

    public function setCloudThumb(string $cloudThumb): void
    {
        $this->cloudThumb = $cloudThumb;
    }
}
