<?php
declare(strict_types = 1);

namespace MigrationBundle\Repository;

use AppBundle\Entity\Cabinet;
use MigrationBundle\Services\Connection;
use Generator;

class CabineteRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Cabinet|Generator
     */
    public function findAll()
    {
        $query = $this->connection->getConnection()->query("SELECT * FROM cabinete");

        while ($cabinet = $query->fetch_assoc()) {
            yield $cabinet;
        }
    }
}
