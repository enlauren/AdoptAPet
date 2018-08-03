<?php

namespace App\NotificationsBundle\Event;

interface CustomEventInterface
{
    const NAME = 'custom.event';

    public function isAsync(): bool;
}
