<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utile
 *
 * @ORM\Table(name="utile")
 * @ORM\Entity
 */
class Utile
{
    /**
     * @var string
     *
     * @ORM\Column(name="nume", type="string", length=255, nullable=false)
     */
    private $nume;

    /**
     * @var string
     *
     * @ORM\Column(name="adresa", type="text", length=65535, nullable=false)
     */
    private $adresa;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="text", length=65535, nullable=false)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="tip", type="string", length=20, nullable=false)
     */
    private $tip;

    /**
     * @var string
     *
     * @ORM\Column(name="detalii", type="text", length=65535, nullable=false)
     */
    private $detalii;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Set nume
     *
     * @param string $nume
     *
     * @return Utile
     */
    public function setNume($nume)
    {
        $this->nume = $nume;

        return $this;
    }

    /**
     * Get nume
     *
     * @return string
     */
    public function getNume()
    {
        return $this->nume;
    }

    /**
     * Set adresa
     *
     * @param string $adresa
     *
     * @return Utile
     */
    public function setAdresa($adresa)
    {
        $this->adresa = $adresa;

        return $this;
    }

    /**
     * Get adresa
     *
     * @return string
     */
    public function getAdresa()
    {
        return $this->adresa;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Utile
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set tip
     *
     * @param string $tip
     *
     * @return Utile
     */
    public function setTip($tip)
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * Get tip
     *
     * @return string
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * Set detalii
     *
     * @param string $detalii
     *
     * @return Utile
     */
    public function setDetalii($detalii)
    {
        $this->detalii = $detalii;

        return $this;
    }

    /**
     * Get detalii
     *
     * @return string
     */
    public function getDetalii()
    {
        return $this->detalii;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
