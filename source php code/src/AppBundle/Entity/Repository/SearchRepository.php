<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use App\AppBundle\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class SearchRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->paginator = $paginator;
        parent::__construct($managerRegistry, Search::class);
    }

    /**
     * @var int
     */
    private $currentBatchSize = 0;

    /**
     * @var int
     */
    private $batchSize = 50;

    /**
     * @param Search $search
     */
    public function save(Search $search)
    {
        $this->_em->persist($search);
        $this->_em->flush($search);
    }

    /**
     * @param Search $classified
     */
    public function saveBatch(Search $classified)
    {
        $this->_em->persist($classified);
        $this->currentBatchSize++;

        if ($this->currentBatchSize % $this->batchSize == 0) {
            $this->_em->flush();
            $this->_em->clear();
        }
    }

    public function flush()
    {
        $this->_em->flush();
        $this->_em->clear();
    }
}
