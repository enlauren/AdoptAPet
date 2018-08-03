<?php

namespace App\NotificationsBundle\Command;

use App\AppBundle\Entity\Repository\ClassifiedRepository;
use DateTime;
use App\NotificationsBundle\Event\AsyncEventSubscriber;
use App\NotificationsBundle\Event\CustomEventInterface;
use App\NotificationsBundle\Event\NotifyEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SendRemindersCommand extends Command
{
    private $interval = 30;

    /**
     * @var ClassifiedRepository
     */
    private $classifiedRepository;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(ClassifiedRepository $classifiedRepository, EventDispatcher $dispatcher)
    {
        $this->classifiedRepository = $classifiedRepository;
        $this->dispatcher = $dispatcher;
    }

    protected function configure()
    {
        $this->setName('pets:reminders:watch')
            ->setDescription('Watch for classifieds that are active and that are posted more than x days ago. Send reminder to authors then update remindedAt.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $producer = $this->getContainer()->get('old_sound_rabbit_mq.upload_picture_producer');
        $dispatcher = $this->getContainer()->get('event_dispatcher');
        $dispatcher->addSubscriber(new AsyncEventSubscriber($producer));

        $olderThanDate = new DateTime($this->interval . ' days ago');

        $results = $classifiedsRepository->findToBeReminded($olderThanDate);

        foreach ($results as $result) {
            $event = new NotifyEvent($result, $result->getUser());
            $dispatcher->dispatch(CustomEventInterface::NAME, $event);
        }
    }
}
