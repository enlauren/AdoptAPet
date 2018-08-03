<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Factory;

use App\AppBundle\Entity\Message;

class MessageFactory
{
    /**
     * @return Message
     */
    public static function create(): Message
    {
        return new Message();
    }
}
