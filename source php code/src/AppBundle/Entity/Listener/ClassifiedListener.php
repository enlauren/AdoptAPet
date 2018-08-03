<?php

namespace App\AppBundle\Entity\Listener;

use App\AppBundle\Entity\Classified;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\Sluggable\Util\Urlizer;

class ClassifiedListener
{
    const DELIMITER = '-';

    /**
     * @var Urlizer
     */
    private $urlizer;

    /**
     * @param Urlizer $urlizer
     */
    public function __construct(Urlizer $urlizer)
    {
        $this->urlizer = $urlizer;
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @return bool
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // todo refactor this class
        // only act on some "Product" entity
        if ($entity instanceof Classified) {
            $classified = $entity;
            if (!$classified->getSlug()) {
                $title     = $classified->getTitle();
                $titleSlug = $this->urlizer->transliterate($title);

                /**
                 * @var ArrayCollection|Classified[] $classifiedArray
                 */
                $classifiedArray = $args->getEntityManager()->getRepository('App\AppBundle:Classified')->findByStartingSlug($titleSlug);
                $existingSlugs   = [];

                if (empty($classifiedArray)) {
                    $classified->setSlug($titleSlug);

                    return true;
                }

                foreach ($classifiedArray as $cl) {
                    $existingSlugs[] = $cl->getSlug();
                }

                $slugFound   = false;
                $startNumber = 1;
                while (!$slugFound) {
                    $newSlug = $titleSlug . '-' . $startNumber;
                    if (!in_array($newSlug, $existingSlugs)) {
                        $slugFound = true;
                    }

                    $startNumber++;
                }

                $classified->setSlug($newSlug);

                return true;
            }
        }
    }
}
