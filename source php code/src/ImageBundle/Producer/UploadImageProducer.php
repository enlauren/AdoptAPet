<?php

namespace App\ImageBundle\Producer;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class UploadImageProducer
{
    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @param ProducerInterface $producer
     * @param string $routingKey
     */
    public function __construct(ProducerInterface $producer, string $routingKey)
    {
        $this->routingKey = $routingKey;
        $this->producer   = $producer;
    }

    /**
     * @param object $msg
     */
    public function publish($msg)
    {
        $this->producer->publish(serialize($msg), $this->routingKey);
    }
}
