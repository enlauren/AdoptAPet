<?php
declare(strict_types = 1);

namespace App\AppBundle\Event;

use App\AppBundle\Entity\Cabinet;
use Symfony\Component\EventDispatcher\Event;

class PetMedicalCenterCreatedEvent extends Event
{
    const NAME = "petMedicalCenter.created";

    /**
     * @var Cabinet
     */
    private $petMedicalCenter;

    /**
     * @param Cabinet $petMedicalCenter
     */
    public function __construct(Cabinet $petMedicalCenter)
    {
        $this->petMedicalCenter = $petMedicalCenter;
    }

    /**
     * @return Cabinet
     */
    public function getPetMedicalCenter(): Cabinet
    {
        return $this->petMedicalCenter;
    }

    /**
     * @param Cabinet $petMedicalCenter
     */
    public function setPetMedicalCenter(Cabinet $petMedicalCenter)
    {
        $this->petMedicalCenter = $petMedicalCenter;
    }
}
