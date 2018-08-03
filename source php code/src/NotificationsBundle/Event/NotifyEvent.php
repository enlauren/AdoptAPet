<?php

namespace App\NotificationsBundle\Event;

use AppBundle\Entity\Classified;
use Symfony\Component\EventDispatcher\Event;
use UserBundle\Entity\User;

class NotifyEvent extends Event implements CustomEventInterface
{
    private $classified;
    private $user;

    public function __construct(Classified $classified, User $user)
    {
        $this->classified = $classified;
        $this->user       = $user;
    }

    public function isAsync(): bool
    {
        return true;
    }

    public function getClassified(): Classified
    {
        return $this->classified;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
