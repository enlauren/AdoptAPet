<?php
declare(strict_types=1);

namespace App\ImageBundle\Service;

use function file_exists;
use function mkdir;

class ImagePathBuilder
{
    const IMAGE_FOLDER_PREFIX = 'anunt';
    const IMAGE_THUMB_FOLDER  = 'thumbs';

    /**
     * @var string
     */
    private $imageDirectory;

    public function __construct(string $imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    public function get(int $classifiedId, string $imageFile, bool $createFolderPath = true): string
    {
        return $this->getDirectory($classifiedId, $createFolderPath) . DIRECTORY_SEPARATOR . $imageFile;
    }

    private function getDirectory(int $classifiedId, bool $createFolderPath = true): string
    {
        $dir = DIRECTORY_SEPARATOR . trim($this->imageDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::IMAGE_FOLDER_PREFIX . $classifiedId;

        if (!file_exists($dir) && $createFolderPath === true) {
            mkdir($dir);
        }

        return $dir;
    }

    public function getThumb(int $classifiedId, string $fileName, bool $createFolderPath = true): string
    {
        $fileImage = $this->getThumbDirectory($classifiedId, $createFolderPath) . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($fileImage)) {
            $fileImage = $this->getOldThumb($fileName);
        }

        return $fileImage;
    }

    private function getThumbDirectory(int $classifiedId, bool $createFolderPath = true): string
    {
        $directory = $this->getDirectory($classifiedId, $createFolderPath) . DIRECTORY_SEPARATOR . self::IMAGE_THUMB_FOLDER;

        if (!file_exists($directory) && $createFolderPath === true) {
            mkdir($directory);
        }

        return $directory;
    }

    private function getOldThumb(string $file): string
    {
        return DIRECTORY_SEPARATOR
            . trim($this->imageDirectory, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . self::IMAGE_THUMB_FOLDER
            . DIRECTORY_SEPARATOR
            . $file;
    }
}
