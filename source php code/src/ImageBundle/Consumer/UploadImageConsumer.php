<?php
declare(strict_types = 1);

namespace App\ImageBundle\Consumer;

use App\ImageBundle\Service\Handler\UploadImageHandler;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class UploadImageConsumer implements ConsumerInterface
{
    /**
     * @var UploadImageHandler
     */
    private $uploadImageHandler;

    /**
     * @param UploadImageHandler $uploadImageHandler
     */
    public function __construct(UploadImageHandler $uploadImageHandler)
    {
        $this->uploadImageHandler = $uploadImageHandler;
    }

    /**
     * @inheritdoc
     */
    public function execute(AMQPMessage $msg)
    {
        $uploadImageCommand = unserialize($msg->getBody());

        try {
            $this->uploadImageHandler->handle($uploadImageCommand);
        } catch (\Exception $exception) {

            var_dump($exception->getMessage());
        }
    }
}
