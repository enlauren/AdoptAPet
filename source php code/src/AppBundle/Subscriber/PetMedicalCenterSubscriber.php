<?php
declare(strict_types = 1);

namespace App\AppBundle\Subscriber;

use App\AppBundle\Event\PetMedicalCenterCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PetMedicalCenterSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            PetMedicalCenterCreatedEvent::NAME => 'onPetMedicalCenterCreate'
        ];
    }

    /**
     * @param PetMedicalCenterCreatedEvent $event
     */
    public function onPetMedicalCenterCreate(PetMedicalCenterCreatedEvent $event)
    {
        // todo send email to owner here
        $petMedicalCenter = $event->getPetMedicalCenter();
    }
}
