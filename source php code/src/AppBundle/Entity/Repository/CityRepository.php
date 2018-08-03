<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use App\AppBundle\Entity\City;
use App\AppBundle\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry, Paginator $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($managerRegistry, City::class);
    }

    /**
     * @return City[]
     */
    public function all()
    {
        return $this->findAll();
    }
}
