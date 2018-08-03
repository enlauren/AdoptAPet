<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

class Message
{
    use Identifiable;

    /**
     * @var Classified
     */
    protected $classified;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var User
     */
    protected $author;

    /**
     * @var string
     */
    protected $content;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return Classified
     */
    public function getClassified() : Classified
    {
        return $this->classified;
    }

    /**
     * @param Classified $classified
     *
     * @return $this
     */
    public function setClassified(Classified $classified)
    {
        $this->classified = $classified;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param User $user
     */
    public function setAuthor(User $user)
    {
        $this->author = $user;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }
}
