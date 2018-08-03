<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

class Search
{
    use Identifiable;

    /**
     * @var string
     */
    private $query;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var boolean
     */
    private $valid = false;

    /**
     * @var integer
     */
    private $count;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @param $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery() : string
    {
        return $this->query;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param boolean $valid
     *
     * @return $this
     */
    public function setValid(bool $valid)
    {
        $this->valid = $valid;
    }

    /**
     * @return boolean
     */
    public function getValid() : bool
    {
        return $this->valid;
    }

    /**
     * @param integer $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return integer
     */
    public function getCount() : int
    {
        return $this->count;
    }

    public function incrementViews()
    {
        $this->count++;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
