<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use App\AppBundle\Interfaces\LinkableInterface;
use App\AppBundle\Entity\Classified;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class City implements LinkableInterface
{
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

    /**
     * @var Cabinet[]|ArrayCollection
     */
    protected $cabinete;

    public function __construct()
    {
        $this->classifieds = new ArrayCollection();
        $this->cabinete    = new ArrayCollection();
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
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Classified[]|ArrayCollection
     */
    public function getClassifieds()
    {
        return $this->classifieds;
    }

    /**
     * @param Classified[]|ArrayCollection $classifieds
     */
    public function setClassifieds($classifieds)
    {
        $this->classifieds = new ArrayCollection($classifieds);
    }

    /**
     * @param Classified $classified
     */
    public function addClassified(Classified $classified)
    {
        $this->classifieds->add($classified);
    }

    public function getIdentifier()
    {
        return $this->getSlug();
    }

    public function getIdentifierName() : string
    {
        return 'slug';
    }

    public function getRoute() : string
    {
        return "listing.city";
    }

    public function __toString() : string
    {
        return $this->getName();
    }

    /**
     * @return Cabinet[]
     */
    public function getCabinete(): array
    {
        return $this->cabinete;
    }

    /**
     * @param Cabinet[] $cabinete
     */
    public function setCabinete(array $cabinete)
    {
        $this->cabinete = $cabinete;
    }
}
