<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use App\AppBundle\Entity\Behaviour\Sluggable;
use App\AppBundle\Entity\Behaviour\Timestampable;
use App\AppBundle\Interfaces\LinkableInterface;
use App\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class Cabinet implements LinkableInterface
{
    use Identifiable, Timestampable, Sluggable;

    /**
     * @var  string
     */
    private $title = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $address = '';

    /**
     * @var string
     */
    private $schedule = '';

    /**
     * @var string
     */
    private $website = '';

    /**
     * @var string
     */
    private $phone = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var boolean
     */
    private $nonstop = false;

    /**
     * @var integer
     */
    private $flag = '0';

    /**
     * @var User
     */
    private $user;

    /**
     * @var Review[]|ArrayCollection
     */
    protected $reviews;

    /**
     * @var City
     */
    private $city;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     */
    public function setSchedule(string $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function isNonstop(): bool
    {
        return $this->nonstop;
    }

    /**
     * @param boolean $nonstop
     */
    public function setNonstop(bool $nonstop)
    {
        $this->nonstop = $nonstop;
    }

    /**
     * @return int
     */
    public function getFlag(): int
    {
        return $this->flag;
    }

    /**
     * @param int $flag
     */
    public function setFlag(int $flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Review[]|ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param Review[]|ArrayCollection $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }

    public function getRoute()
    {
        return 'vet.single';
    }

    public function getIdentifier()
    {
        return $this->getSlug();
    }

    public function getIdentifierName()
    {
        return 'slug';
    }
}
