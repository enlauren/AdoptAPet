<?php

namespace App\ImageBundle\Command;

use App\ImageBundle\Producer\UploadImageProducer;
use App\ImageBundle\Service\Command\UploadImageCommand;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestUploadCommand extends Command
{
    /**
     * @var ProducerInterface
     */
    private $producer;

    public function __construct(UploadImageProducer $producer)
    {
        parent::__construct();

        $this->producer = $producer;
    }


    public function configure()
    {
        $this->setName('test:image')
            ->addArgument('image');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = file_get_contents(__DIR__ . '/../Resources/config/test.png');

        $command = new UploadImageCommand(
            1, 1, $content, '.png'
        );

        $this->producer->publish($command);

        $output->writeln('Image published!');
    }
}
