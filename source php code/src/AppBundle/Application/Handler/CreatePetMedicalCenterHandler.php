<?php
declare(strict_types = 1);

namespace App\AppBundle\Application\Handler;

use App\AppBundle\Application\Command\CreatePetMedicalCenterCommand;
use App\AppBundle\Entity\Repository\CabineteRepository;
use App\AppBundle\Event\PetMedicalCenterCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreatePetMedicalCenterHandler
{
    /**
     * @var CabineteRepository
     */
    private $petMedicalCenterRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param CabineteRepository       $petMedicalCenterRepository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        CabineteRepository $petMedicalCenterRepository,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->petMedicalCenterRepository = $petMedicalCenterRepository;
        $this->dispatcher                 = $dispatcher;
    }

    /**
     * @param CreatePetMedicalCenterCommand $command
     */
    public function handle(CreatePetMedicalCenterCommand $command)
    {
        $this->petMedicalCenterRepository->save($command->getPetMedicalCenter());

        $event = new PetMedicalCenterCreatedEvent($command->getPetMedicalCenter());
        $this->dispatcher->dispatch(PetMedicalCenterCreatedEvent::NAME, $event);
    }
}
