<?php
declare(strict_types=1);

namespace App\ImageBundle\Service\Handler;

use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use Exception;
use App\ImageBundle\Entity\Repository\ImageRepository;
use App\ImageBundle\Producer\UploadImageProducer;
use App\ImageBundle\Service\Command\UploadImageCommand;
use App\ImageBundle\Service\Handler\ImageType\ImageTypeInterface;
use App\ImageBundle\Service\Handler\ImageType\OptimisedImageType;
use App\ImageBundle\Service\Handler\ImageType\OriginalImageType;
use App\ImageBundle\Service\Handler\ImageType\ThumbImageType;
use App\ImageBundle\Service\ImageFactory;
use App\ImageBundle\Service\ImagePathBuilderOpenCloud;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManager;
use OpenCloud\OpenStack;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class UploadImageHandler
{
    const OVH_OPENSTACK_URL            = 'https://auth.cloud.ovh.net/v2.0';
    const OVH_OPENSTACK_USERNAME       = 'MS4e2tnn8gss';
    const OVH_OPENSTACK_PASSWORD       = 'dfYYXHdCddhCEncMJuTgHJFhEC78Bhav';
    const OVH_OPENSTACK_PROJECT_ID     = 'c9949255ef13486c870ca554940b1fd5';
    const SERVICE_NAME                 = 'swift';
    const OVH_OPENSTACK_REGION         = 'DE1';
    const OVH_OPENSTACK_CONTAINER_NAME = 'images';

    /** @var ImageFactory */
    protected $imageFactory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var ClassifiedRepository */
    private $classifiedRepository;

    /** @var UploadImageProducer */
    private $producer;

    /** @var ImagePathBuilderOpenCloud */
    private $imagePathBuilder;

    /** @var ImageRepository */
    private $imageRepository;

    /** @var ImageTypeInterface[] */
    private $imageTypes = [];

    public function __construct(
        ImageFactory $imageFactory,
        ClassifiedRepository $classifiedRepository,
        UploadImageProducer $producer,
        ImagePathBuilderOpenCloud $imagePathBuilder,
        LoggerInterface $logger,
        ImageRepository $imageRepository
    )
    {
        $this->imageFactory         = $imageFactory;
        $this->classifiedRepository = $classifiedRepository;
        $this->producer             = $producer;
        $this->imagePathBuilder     = $imagePathBuilder;
        $this->logger               = $logger;
        $this->imageRepository      = $imageRepository;

        $this->imageTypes[] = new OriginalImageType();
        $this->imageTypes[] = new OptimisedImageType(
            $this->imageRepository,
            $this->classifiedRepository,
            $this->logger
        );

        $this->imageTypes[] = new ThumbImageType(
            $this->imageRepository
        );
    }

    /**
     * @param UploadImageCommand $uploadImageCommand
     * @return bool
     * @throws Exception
     */
    public function handle(UploadImageCommand $uploadImageCommand): bool
    {
        print 'Starting to upload image for classified: ' . $uploadImageCommand->getClassifiedId() . PHP_EOL;

        $imageManager = new ImageManager(['driver' => 'imagick']);
        $image        = $imageManager->make($uploadImageCommand->getContent());
        $image->getCore()->stripImage();

        /** @var Classified $classified */
        $classified = $this->classifiedRepository->find($uploadImageCommand->getClassifiedId());

        if (!$classified) {
            $this->logger->error('Tried to save image file for an invalid classified.', [
                'classifiedId' => $uploadImageCommand->getClassifiedId(),
                'extension'    => $uploadImageCommand->getExtension(),
            ]);

            throw new Exception('Tried to save image file for an invalid classified.');
        }

        $allHttpHeaders = ['Cache-Control' => 'max-age=2592000, public'];
        $client         = new OpenStack(self::OVH_OPENSTACK_URL, [
            'username' => self::OVH_OPENSTACK_USERNAME,
            'password' => self::OVH_OPENSTACK_PASSWORD,
            'tenantId' => self::OVH_OPENSTACK_PROJECT_ID,
        ]);

        $objectStoreService = $client->objectStoreService(self::SERVICE_NAME, self::OVH_OPENSTACK_REGION);
        $container          = $objectStoreService->getContainer(self::OVH_OPENSTACK_CONTAINER_NAME);

        foreach ($this->imageTypes as $imageType) {
            print 'Starting to upload image ... ' . PHP_EOL;
            $imageName = $imageType->getDirectoryPrefix() . $classified->getSlug() . '-' . $imageType->getName() . '.jpg';
            $imagePath = $this->imagePathBuilder->get($classified->getId(), $imageName);

            $container->uploadObject($imagePath, $imageType->getTransformed($image), $allHttpHeaders);
            print '111 ' . PHP_EOL;
            $imageType->save($uploadImageCommand, $imagePath, $classified);
            print '... uploaded' . PHP_EOL;
        }

        print 'Uploaded image for classified ' . $uploadImageCommand->getClassifiedId() . PHP_EOL;

        return true;
    }
}
