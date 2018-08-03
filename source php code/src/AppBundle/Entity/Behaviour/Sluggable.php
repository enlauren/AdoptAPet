<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Behaviour;

use Gedmo\Sluggable\Util\Urlizer;

trait Sluggable
{
    /**
     * @var string
     */
    protected $slug = '';

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
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }
}
