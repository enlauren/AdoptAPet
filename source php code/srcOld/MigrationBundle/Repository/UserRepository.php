<?php
declare(strict_types = 1);

namespace MigrationBundle\Repository;

use MigrationBundle\Services\Connection;

class UserRepository
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

    public function findAll()
    {
        $query = $this->connection->getConnection()->query("SELECT * FROM users");

        while ($user = $query->fetch_assoc()) {
            yield $user;
        }
    }
}
