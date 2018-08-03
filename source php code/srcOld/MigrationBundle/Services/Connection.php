<?php
declare(strict_types = 1);

namespace MigrationBundle\Services;

use mysqli;

class Connection
{
    /**
     * @var mysqli
     */
    protected $connection;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pass;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $dbName
     */
    public function __construct(string $host, string $user, string $pass, string $dbName)
    {
        $this->host   = $host;
        $this->user   = $user;
        $this->pass   = $pass;
        $this->dbName = $dbName;

        $this->connect();
    }

    private function connect()
    {
        $this->connection = \mysqli_connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbName
        );
    }

    /**
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
