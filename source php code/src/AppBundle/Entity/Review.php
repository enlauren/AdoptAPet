<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use App\AppBundle\Entity\Behaviour\SoftDeleteable;
use App\AppBundle\Entity\Behaviour\Timestampable;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

class Review
{
    use Identifiable, Timestampable, SoftDeleteable;

    /**
     * @var Cabinet
     */
    protected $cabinet;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var integer
     */
    protected $rating;

    /**
     * @var boolean
     */
    protected $approved;

    /**
     * @var integer
     */
    protected $flag;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Cabinet $cabinet
     */
    public function setCabinet($cabinet)
    {
        $this->cabinet = $cabinet;
    }

    /**
     * @return Cabinet
     */
    public function getCabinet()
    {
        return $this->cabinet;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param integer $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param integer $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return integer
     */
    public function getFlag()
    {
        return $this->flag;
    }
}
