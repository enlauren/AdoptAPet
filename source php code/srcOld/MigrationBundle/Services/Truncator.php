<?php
declare(strict_types = 1);

namespace MigrationBundle\Services;

use Doctrine\DBAL\Connection;

class Truncator
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function truncate()
    {
        $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
        $this->connection->query('TRUNCATE TABLE users');
        $this->connection->query('TRUNCATE TABLE classified');
        $this->connection->query('TRUNCATE TABLE classifieds_to_cities');
        $this->connection->query('TRUNCATE TABLE image');
        $this->connection->query('TRUNCATE TABLE search');
        $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
