<?php
declare(strict_types=1);

namespace App\UserBundle\Entity;

use App\AppBundle\Entity\Behaviour\Identifiable;
use App\AppBundle\Entity\Behaviour\SoftDeleteable;
use App\AppBundle\Entity\Behaviour\Timestampable;
use App\AppBundle\Entity\Cabinet;
use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Feedback;
use App\AppBundle\Entity\Message;
use App\AppBundle\Interfaces\LinkableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements LinkableInterface, UserInterface
{
    use Identifiable, Timestampable, SoftDeleteable;

    const ROLE_USER  = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var integer
     */
    protected $active;

    /**
     * @var string
     */
    protected $plainPassword = '';

    /**
     * @var string
     */
    protected $rememberToken;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var ArrayCollection|Message[]
     */
    protected $messages;

    /**
     * @var Classified[]|ArrayCollection
     */
    protected $classifieds;

    /**
     * @var Feedback[]
     */
    protected $feedbacks;

    /**
     * @var Cabinet[]
     */
    protected $cabinete;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var string
     */
    protected $password;

    /** @var boolean */
    protected $isAdmin = false;

    /**
     * @var string
     */
    private $code;

    public function __construct()
    {
        $this->classifieds = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->cabinete = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
        $this->active = true;
        $this->salt = 'asdasdasdqweqweqweqweqw'; // todo change this shit
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used during check for
     * changes and the id.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->password,
            $this->salt,
            $this->username,
            $this->id,
            $this->email,
        ]);
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->id,
            $this->expiresAt,
            $this->credentialsExpireAt,
            $this->email,
            $this->emailCanonical
            ) = $data;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = [
            self::ROLE_USER
        ];

        if (true === $this->isAdmin()) {
            $roles[] = self::ROLE_ADMIN;
        }

        return $roles;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return ArrayCollection|Classified[]
     */
    public function getClassifieds()
    {
        return $this->classifieds;
    }

    /**
     * @param $classifieds
     */
    public function setClassifieds($classifieds)
    {
        $this->classifieds[] = $classifieds;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    /**
     * @param Classified $classified
     */
    public function addClassified(Classified $classified)
    {
        $classified->setUser($this);
        $this->classifieds->add($classified);
    }

    /**
     * @inheritdoc
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
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
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $passwordTemp
     */
    public function setPasswordTemp($passwordTemp)
    {
        $this->passwordTemp = $passwordTemp;
    }

    /**
     * @return string
     */
    public function getPasswordTemp()
    {
        return $this->passwordTemp;
    }

    /**
     * @param integer $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return integer
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $rememberToken
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
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
     * @return ArrayCollection|Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return Message[] $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages->add($message);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return 'user_listing';
    }

    /**
     * @return integer
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getIdentifierName()
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code = null)
    {
        $this->code = $code;
    }
}
