<?php

namespace App\NotificationsBundle\Consumer;

use AppBundle\Services\Mailer;
use Exception;
use App\NotificationsBundle\Event\NotifyEvent;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use function var_dump;

class NotificationsSenderConsumer implements ConsumerInterface
{
    /** @var Mailer */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @inheritdoc
     */
    public function execute(AMQPMessage $msg)
    {
        /** @var NotifyEvent $notification */
        $notification = unserialize($msg->getBody());

        try {
            $this->mailer->notifyUserWithReminder($notification->getUser(), $notification->getClassified());
        } catch (Exception $exception) {
            // todo handle exception
        }
    }
}
