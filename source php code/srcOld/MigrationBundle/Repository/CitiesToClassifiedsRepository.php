<?php
declare(strict_types = 1);

namespace MigrationBundle\Repository;

use MigrationBundle\Services\Connection;

class CitiesToClassifiedsRepository
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

    /**
     * @param int $classifiedId
     * @return array
     */
    public function findByClassifiedId($classifiedId)
    {
        $result = $this->connection->getConnection()->query("SELECT * FROM pets_jud WHERE pet_id = " . $classifiedId);

        while ($city = $result->fetch_assoc()) {
            yield $city;
        }
    }
}
