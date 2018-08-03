<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use App\AppBundle\Entity\Message;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    /**
     * @param Message $message
     * @param bool    $flush
     */
    public function save(Message $message, bool $flush = false)
    {
        $this->_em->persist($message);

        if (true === $flush) {
            $this->_em->flush($message);
        }
    }
}
