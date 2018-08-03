<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Behaviour;

trait Identifiable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
