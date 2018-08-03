<?php
declare(strict_types = 1);

namespace MigrationBundle\Repository;

use MigrationBundle\Services\Connection;

class ImageRepository
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
     * @return \Generator|array
     */
    public function findAll()
    {
        $query = $this->connection->getConnection()->query("SELECT * FROM images");

        while ($cabinet = $query->fetch_assoc()) {
            yield $cabinet;
        }
    }

    /**
     * @param int $id
     * @return \Generator
     */
    public function findByClassifiedId($id)
    {
        $query = $this->connection->getConnection()->query("SELECT * FROM images where pet_id=" . $id);

        while ($cabinet = $query->fetch_assoc()) {
            yield $cabinet;
        }
    }
}
