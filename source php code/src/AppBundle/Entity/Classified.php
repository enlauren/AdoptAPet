<?php
declare(strict_types=1);

namespace App\AppBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use App\AppBundle\Entity\Behaviour\Sluggable;
use App\AppBundle\Entity\Behaviour\SoftDeleteable;
use App\AppBundle\Entity\Behaviour\Timestampable;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\AppBundle\Interfaces\LinkableInterface;
use Gedmo\Sluggable\Util\Urlizer;
use App\ImageBundle\Entity\Image;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\Date;
use App\UserBundle\Entity\User;

class Classified implements LinkableInterface
{
    use Identifiable, Timestampable, SoftDeleteable, Sluggable;

    const SHORT_DESCRIPTION_LENGTH = 200;

    /**
     * @var string
     * @Groups({"elastica"})
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * Date when author last refreshed the classified
     *
     * @var DateTime
     */
    protected $refreshedAt;

    /**
     * Date when we've send last email reminder to author
     *
     * @var DateTime
     */
    protected $remindedAt;

    /**
     * How many reminders we've sent to the author
     * @var integer
     */
    protected $reminders = '0';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $phone = '';

    /**
     * @var integer
     */
    protected $expired = '0';

    /**
     * @var integer
     */
    protected $views = '0';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var City[]|ArrayCollection
     */
    protected $cities;

    /**
     * @var string
     */
    protected $gender = '';

    /**
     * How many times the classified was flagged
     * @var integer
     */
    protected $flag = '0';

    /**
     * @var string
     */
    protected $token;

    /**
     * @var integer
     */
    protected $priority = '0';

    /**
     * @var string
     */
    protected $ip;

    /** @var ArrayCollection */
    protected $messages;

    /** @var bool */
    protected $allowedComments = true;

    /** @var int */
    private $oldId;

    /** @var bool */
    private $approved;

    /** @var Image[] */
    protected $images;

    /** @var Classified */
    protected $canonical;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->images = new ArrayCollection();

        $this->createdAt   = new DateTime();
        $this->remindedAt  = new DateTime();
        $this->updatedAt   = new DateTime();
        $this->refreshedAt = new DateTime();
        $this->token       = md5(random_bytes(12));
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
    public function getTitle()
    {
        return $this->title;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param DateTime $refreshedAt
     */
    public function setRefreshedAt(DateTime $refreshedAt)
    {
        $this->refreshedAt = $refreshedAt;
    }

    /**
     * @return DateTime
     */
    public function getRefreshedAt()
    {
        return $this->refreshedAt;
    }

    /**
     * @param DateTime $remindedAt
     */
    public function setRemindedAt(DateTime $remindedAt)
    {
        $this->remindedAt = $remindedAt;
    }

    /**
     * @return DateTime
     */
    public function getRemindedAt()
    {
        return $this->remindedAt;
    }

    /**
     * @param integer $reminders
     */
    public function setReminders(int $reminders)
    {
        $this->reminders = $reminders;
    }

    /**
     * @return integer
     */
    public function getReminders()
    {
        return $this->reminders;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param bool $expired
     */
    public function setExpired(bool $expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return bool|int
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expired;
    }

    /**
     * @param integer $views
     */
    public function setViews(int $views)
    {
        $this->views = $views;
    }

    /**
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
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
    public function getFlag(): int
    {
        return $this->flag;
    }

    /**
     * @return Image|string
     * @throws Exception
     */
    public function getThumb(): Image
    {
        if ($this->images[0] instanceof Image) {
            return $this->images[0];
        }

        // TODO make Exception specific
        throw new Exception('classified does not have thumb. Call hasThumb first');
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param integer $priority
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function hasAllowedComments()
    {
        return $this->allowedComments;
    }

    /**
     * @param boolean $allowedComments
     */
    public function setAllowedComments(bool $allowedComments)
    {
        $this->allowedComments = $allowedComments;
    }

    /**
     * @return bool
     */
    public function hasThumb()
    {
        return $this->images[0] instanceof Image;
    }

    public function getRoute()
    {
        return 'classified.single';
    }

    public function getIdentifier()
    {
        return $this->getSlug();
    }

    public function getIdentifierName()
    {
        return 'slug';
    }

    public function getShortDescription()
    {
        $shortDescription = $this->getDescription();

        if (strlen($shortDescription) > self::SHORT_DESCRIPTION_LENGTH) {
            $shortDescription = substr($shortDescription, 0, self::SHORT_DESCRIPTION_LENGTH - 3) . '...';
        }

        return $shortDescription;
    }

    public function getPrettyCreatedAt()
    {
        return $this->getCreatedAt()->format('Y-m-d H:i:s');
    }

    /**
     * @return City[]
     */
    public function getCities()
    {
        return $this->cities;
    }

    public function isFemale(): bool
    {
        return $this->getGender() == 'f';
    }

    public function isMale(): bool
    {
        return $this->getGender() == 'm';
    }

    public function hasAuthor()
    {
        return $this->getEmail() != '';
    }

    public function hasImages()
    {
        return $this->getThumb() != "";
    }

    /**
     * @return Image[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @param City[]|ArrayCollection $cities
     */
    public function setCities($cities)
    {
        $this->cities = $cities;
    }

    /**
     * @param City $city
     */
    public function addCity(City $city)
    {
        $this->cities->add($city);
    }

    public function getViewIdentifier()
    {
        return 'classified';
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return int
     */
    public function getOldId()
    {
        return $this->oldId;
    }

    /**
     * @param int $oldId
     */
    public function setOldId(int $oldId)
    {
        $this->oldId = $oldId;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * Approve by admin through email or dashboard.
     *
     * @param bool $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return Classified
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * @param Classified $canonical
     */
    public function setCanonical(Classified $canonical)
    {
        $this->canonical = $canonical;
    }

    /**
     * If has comments approved and can receive messages from other users
     */
    public function canReceiveMessages()
    {
        return !$this->isExpired() && $this->getUser()->getEmail() && $this->isApproved();
    }
}
