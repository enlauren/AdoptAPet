<?php

namespace App\NotificationsBundle\Event;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use function serialize;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function var_dump;

class AsyncEventSubscriber implements EventSubscriberInterface
{
    private $producer;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    static function getSubscribedEvents()
    {
        return [
            CustomEventInterface::NAME => [
                'doStuff'
            ]
        ];
    }

    public function doStuff(CustomEventInterface $customEvent)
    {
        if ($customEvent->isAsync()) {
            $this->producer->publish(serialize($customEvent), 'event.custom');
        }
    }
}
