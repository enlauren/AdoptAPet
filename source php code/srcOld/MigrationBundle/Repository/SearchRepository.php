<?php
declare(strict_types = 1);

namespace MigrationBundle\Repository;

use AppBundle\Entity\Cabinet;
use MigrationBundle\Services\Connection;
use Generator;

class SearchRepository
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
     * @return array|Generator
     */
    public function findAll()
    {
        $query = $this->connection->getConnection()->query("SELECT * FROM cautari");

        while ($search = $query->fetch_assoc()) {
            yield $search;
        }
    }
}
