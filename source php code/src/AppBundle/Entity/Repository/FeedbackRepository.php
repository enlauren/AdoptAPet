<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use App\AppBundle\Entity\Feedback;
use Doctrine\ORM\EntityRepository;

class FeedbackRepository extends EntityRepository
{
    /**
     * @param Feedback $feedback
     */
    public function save(Feedback $feedback)
    {
        $this->_em->persist($feedback);
        $this->_em->flush($feedback);
    }
}
