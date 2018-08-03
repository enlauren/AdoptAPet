<?php
declare(strict_types = 1);

namespace App\ImageBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\ImageBundle\Entity\Image;
use Doctrine\Common\Persistence\ManagerRegistry;

class ImageRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry, Image::class);
    }

    /**
     * @param Image $image
     * @param bool  $flush
     */
    public function save(Image $image, bool $flush = true)
    {
        $this->_em->persist($image);

        if (true === $flush) {
            $this->_em->flush($image);
        }
    }

    /**
     * @param string $file
     * @return Image
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOldThumb(string $file)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.thumb = :file')
            ->setParameter('file', $file)
            ->orderBy('i.id', 'ASC');

        return $qb->getQuery()->getSingleResult();
    }
}
