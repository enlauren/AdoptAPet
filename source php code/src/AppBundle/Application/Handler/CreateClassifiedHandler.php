<?php
declare(strict_types=1);

namespace App\AppBundle\Application\Handler;

use App\AppBundle\Application\Command\CreateClassifiedCommand;
use App\AppBundle\Entity\Repository\ClassifiedRepository;
use App\AppBundle\Services\Mailer;
use App\ImageBundle\Producer\UploadImageProducer;
use App\ImageBundle\Service\Command\UploadImageCommand;
use App\UserBundle\Provider\UserProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function uniqid;

class CreateClassifiedHandler
{
    /** @var ClassifiedRepository */
    private $classifiedRepository;

    /** @var UserProvider */
    private $userProvider;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var UploadImageProducer
     */
    private $producer;

    /**
     * @param ClassifiedRepository $classifiedRepository
     * @param UserProvider         $userProvider
     * @param Mailer               $mailer
     * @param UploadImageProducer  $producer
     */
    public function __construct(
        ClassifiedRepository $classifiedRepository,
        UserProvider $userProvider,
        Mailer $mailer,
        UploadImageProducer $producer
    )
    {
        $this->classifiedRepository = $classifiedRepository;
        $this->userProvider         = $userProvider;
        $this->mailer               = $mailer;
        $this->producer             = $producer;
    }

    /**
     * Create a classified
     *
     * @param CreateClassifiedCommand $command
     * @throws OptimisticLockException
     * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(CreateClassifiedCommand $command)
    {
        $classified = $command->getClassified();
        $images     = $classified->getImages();

        $classified->setImages(new ArrayCollection());

        $user = $this->userProvider
            ->getFromEmailAndPhone(
                $classified->getEmail(),
                $classified->getPhone()
            );

        $classified->setUser($user);

        try {
            $this->classifiedRepository->save($classified);
        } catch (UniqueConstraintViolationException $exception) {
            $slug = $classified->getSlug();
            $slug = $slug . '-' . uniqid();
            $classified->setSlug($slug);

            $this->classifiedRepository->save($classified);
        } catch (OptimisticLockException $e) {
            //do stuff
        }

        /** @var UploadedFile $image */
        foreach ($images as $image) {
            $uploadImageCommand = new UploadImageCommand(
                $classified->getId(),
                null,
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalExtension()
            );

            $this->producer->publish($uploadImageCommand);
        }

        $this->mailer->sendCreateMessage($user, $classified);
        $this->mailer->notifyAdmin($user, $classified);
    }
}
