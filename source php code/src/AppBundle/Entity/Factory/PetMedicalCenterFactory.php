<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Factory;

use App\AppBundle\Entity\Cabinet;

class PetMedicalCenterFactory
{
    /**
     * @return Cabinet
     */
    public function create()
    {
        return new Cabinet();
    }
}
