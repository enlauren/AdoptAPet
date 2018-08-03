<?php

namespace App\ImageBundle\Command;

use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use App\ImageBundle\Producer\UploadImageProducer;
use App\ImageBundle\Service\ImagePathBuilder;
use function file_get_contents;
use App\ImageBundle\Service\Command\UploadImageCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImageScheduleCommand extends Command
{
    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;

    /**
     * @var ImagePathBuilder
     */
    private $imagePathBuilder;

    /**
     * @var UploadImageProducer
     */
    private $uploadImageProducer;

    public function __construct(
        ClassifiedRepository $classifiedRepository,
        ImagePathBuilder $imagePathBuilder,
        UploadImageProducer $uploadImageProducer
    ) {
        $this->classifiedRepository = $classifiedRepository;
        $this->imagePathBuilder = $imagePathBuilder;
        $this->uploadImageProducer = $uploadImageProducer;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('pets:images:move_to_cdn')
            ->setDescription('Move old classifieds images to ovh cloud openstack.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classifiedRepository = $this->classifiedRepository;
        $imagePathBuilder     = $this->imagePathBuilder;
        $uploadImageProducer  = $this->uploadImageProducer;

        /** @var Classified $classified */
        foreach ($classifiedRepository->findAllMostRecent() as $classified) {
            $count = 0;

            foreach ($classified->getImages() as $image) {
                $mainPath  = $imagePathBuilder->get($classified->getId(), $image->getFile(), false);
                $thumbPath = $imagePathBuilder->getThumb($classified->getId(), $image->getFile(), false);

                if (!file_exists($mainPath)) {
                    continue;
                }

                $command = new UploadImageCommand(
                    $classified->getId(),
                    $image->getId(),
                    file_get_contents($mainPath),
                    ''
                );

                $uploadImageProducer->publish($command);
                $count++;
            }

            $output->writeln('Scheduled for upload ' . $count . ' images for: ' . $classified->getTitle());
        }
    }
}
