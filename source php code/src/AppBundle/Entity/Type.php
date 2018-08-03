<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use App\AppBundle\Interfaces\LinkableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class Type implements LinkableInterface
{
    const TYPE_DOG = 'caini';
    const TYPE_CAT = 'pisici';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var Classified[]|ArrayCollection
     */
    private $classifieds;

    public function __construct()
    {
        $this->classifieds = new ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
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

    /**
     * @return bool
     */
    public function isDog()
    {
        return $this->slug == self::TYPE_DOG;
    }

    /**
     * @return bool
     */
    public function isCat()
    {
        return $this->slug == self::TYPE_CAT;
    }

    public function getViewIdentifier()
    {
        return 'type';
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return 'adoptii.type';
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getSlug();
    }

    /**
     * @return mixed
     */
    public function getIdentifierName()
    {
        return 'slug';
    }

    public function __toString()
    {
        return $this->getName();
    }
}
