<?php
declare(strict_types = 1);

namespace App\AppBundle\Application\Command;

use App\AppBundle\Entity\Classified;

class CreateClassifiedCommand
{
    /**
     * @var Classified
     */
    private $classified;

    /**
     * @param Classified $classified
     */
    public function __construct(Classified $classified)
    {
        $this->classified = $classified;
    }

    /**
     * @return Classified
     */
    public function getClassified(): Classified
    {
        return $this->classified;
    }
}
