<?php

namespace MigrationBundle\Command;

use MigrationBundle\Services\Migrator;
use MigrationBundle\Services\UserMigrator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pets:migrate')
            ->setDescription('Migrate old laravel database structure to the new symfony structure.')
            ->setHelp("This command allows you to create users...");
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('truncator')->truncate();

        $userMigrator       = $this->getContainer()->get('migrator.user');
        $classifiedMigrator = $this->getContainer()->get('migrator.classified');
        $cabineteMigrator   = $this->getContainer()->get('migrator.cabinete');
        $searchMigrator     = $this->getContainer()->get('migrator.search');

        $userMigrator->createUsersFromClassifieds();
        $classifiedMigrator->migrateClassifieds();
        $classifiedMigrator->associateCitiesToClassifieds();
        $cabineteMigrator->migrate();
        $searchMigrator->migrate();

        return 0;
    }
}
