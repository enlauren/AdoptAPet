<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use App\AppBundle\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Type::class);
    }

    /**
     * @return Type[]
     */
    public function all()
    {
        return $this->findAll();
    }
}
