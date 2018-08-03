<?php

namespace MigrationBundle\Repository;


use MigrationBundle\Services\Connection;

class ClassifiedRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $query = $this->connection->getConnection()->query("SELECT * FROM pets");

        while ($classified = $query->fetch_assoc()) {
            yield $classified;
        }
    }
}
