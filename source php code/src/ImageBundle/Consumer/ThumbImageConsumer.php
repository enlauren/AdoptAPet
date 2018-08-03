<?php
declare(strict_types = 1);

namespace App\ImageBundle\Consumer;

use App\ImageBundle\Service\Handler\CreateImageThumbHandler;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ThumbImageConsumer implements ConsumerInterface
{
    /**
     * @var CreateImageThumbHandler
     */
    private $createImageThumbHandler;

    /**
     * @param CreateImageThumbHandler $createImageThumbHandler
     */
    public function __construct(CreateImageThumbHandler $createImageThumbHandler)
    {
        $this->createImageThumbHandler = $createImageThumbHandler;
    }

    /**
     * @inheritdoc
     */
    public function execute(AMQPMessage $msg)
    {
        $createThumbCommand = unserialize($msg->getBody());
        $this->createImageThumbHandler->handle($createThumbCommand);
    }
}
