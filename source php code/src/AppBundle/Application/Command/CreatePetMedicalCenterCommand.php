<?php
declare(strict_types = 1);

namespace App\AppBundle\Application\Command;

use App\AppBundle\Entity\Cabinet;

class CreatePetMedicalCenterCommand
{
    /**
     * @var Cabinet
     */
    protected $petMedicalCenter;

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
