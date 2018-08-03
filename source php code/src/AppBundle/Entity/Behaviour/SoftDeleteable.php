<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Behaviour;

use DateTime;

trait SoftDeleteable
{
    /**
     * @var DateTime
     */
    protected $deletedAt;

    /**
     * @return DateTime
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime $deletedAt
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}
