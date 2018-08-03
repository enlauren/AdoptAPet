<?php
declare(strict_types = 1);

namespace App\ImageBundle\Service;

class ImagePathBuilderOpenCloud
{
    const IMAGE_FOLDER_PREFIX = 'classified';
    const IMAGE_THUMB_FOLDER  = 'thumbs';

    /**
     * @param int $classifiedId
     * @param string $imageFile
     * @return string
     */
    public function get($classifiedId, $imageFile): string
    {
        return $this->getDirectory($classifiedId) . DIRECTORY_SEPARATOR . $imageFile;
    }

    /**
     * @param int $classifiedId
     * @return string
     */
    public function getDirectory(int $classifiedId): string
    {
        return self::IMAGE_FOLDER_PREFIX . $classifiedId;
    }

    /**
     * @param int $classifiedId
     * @param string $fileName
     * @return string
     */
    public function getThumb($classifiedId, $fileName): string
    {
        return $this->getThumbDirectory($classifiedId) . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param int $classifiedId
     * @return string
     */
    public function getThumbDirectory(int $classifiedId): string
    {
        return $this->getDirectory($classifiedId) . DIRECTORY_SEPARATOR . self::IMAGE_THUMB_FOLDER;
    }
}
