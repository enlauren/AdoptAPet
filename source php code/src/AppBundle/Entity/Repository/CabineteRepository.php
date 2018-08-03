<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use App\AppBundle\Entity\Cabinet;
use App\AppBundle\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;

class CabineteRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry, Cabinet::class);
    }

    /**
     * @param City $city
     *
     * @return QueryBuilder
     */
    public function findByCity(City $city)
    {
        $qb = $this->createQueryBuilder('mc');
        $qb->andWhere('mc.city = :city')
            ->setParameter(':city', $city);

        return $qb;
    }

    /**
     * @param Cabinet $getPetMedicalCenter
     * @param bool    $flush
     */
    public function save($getPetMedicalCenter, bool $flush = true)
    {
        $this->_em->persist($getPetMedicalCenter);

        if (true === $flush) {
            $this->_em->flush($getPetMedicalCenter);
        }
    }
}
