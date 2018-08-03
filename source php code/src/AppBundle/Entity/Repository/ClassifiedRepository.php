<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Repository;

use AdminBundle\Model\StatusMap;
use App\AppBundle\Entity\City;
use App\AppBundle\Entity\Classified;
use App\AppBundle\Entity\Type;
use App\AppBundle\Services\Paginator;
use App\UserBundle\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ClassifiedRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $managerRegistry,
        Paginator $paginator
    )
    {
        $this->paginator = $paginator;
        parent::__construct($managerRegistry, Classified::class);
    }
    
    /** @var int */
    private $batchSize = 200;

    /**
     * @var int
     */
    private $currentBatchSize = 0;

    /**
     * @var Classified[]
     */
    protected $related = [];

    /**
     * @var int
     */
    protected $perPageResults;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @param Classified $classified
     *
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     */
    public function save(Classified $classified, $flush = true)
    {
        $this->_em->persist($classified);

        if (true === $flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $pageNo
     *
     * @return PaginationInterface
     */
    public function page($pageNo)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->andWhere('c.approved = 1')
            ->orderBy('c.priority', 'DESC')
            ->addOrderBy('c.refreshedAt', 'DESC');

        $pagination = $this->paginator->paginate(
            $qb,
            $pageNo,
            $this->perPageResults
        );

        return $pagination;
    }

    /**
     * @param $pageNo
     *
     * @return PaginationInterface
     */
    public function rawPage($pageNo, $status = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->addOrderBy('c.createdAt', 'DESC');

        if (null !== $status) {

            if (StatusMap::$map[$status] === null) {
                $qb->andWhere('c.approved IS NULL');
            }
        }

        $pagination = $this->paginator->paginate(
            $qb->getQuery(),
            $pageNo,
            $this->perPageResults
        );

        return $pagination;
    }

    /**
     * @param City $city
     * @param int  $pageNo
     *
     * @return PaginationInterface
     */
    public function findByCityAndPaginate(City $city, int $pageNo)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->andWhere('c.approved = 1')
            ->andWhere(':city MEMBER OF c.cities')
            ->setParameter(':city', $city)
            ->orderBy('c.priority', 'DESC')
            ->addorderBy('c.refreshedAt', 'DESC');


        return $this->paginateQuery($qb, $pageNo);
    }

    /**
     * @param User $user
     * @param int  $pageNo
     *
     * @return PaginationInterface
     */
    public function findByUserAndPaginate(User $user, int $pageNo)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.expired = 0')
            ->andWhere('c.approved = 1')
            ->andWhere('c.user = :user')
            ->setParameter(':user', $user)
            ->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb, $pageNo);
    }

    /**
     * @return Classified[]
     */
    public function getLatest()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.refreshedAt', 'DESC')
            ->where('c.expired = 0')
            ->andWhere('c.approved = 1')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Classified $classified
     * @param Type|null  $type
     *
     * @return Classified[]
     */
    public function getRelatedByCities(Classified $classified, $type = null)
    {
        $related = [];

        foreach ($classified->getCities() as $city) {
            $classifieds = $this->getRelatedByCity($classified, $city, $type);
            if ($classifieds) {
                $related[$city->getName()] = $classifieds;
            }
        }

        return $related;
    }

    /**
     * @param Classified $classified
     * @param City       $city
     * @param null       $type
     * @param bool       $excludeBefore
     *
     * @return Classified[]
     */
    public function getRelatedByCity(Classified $classified, City $city, $type = null, $excludeBefore = true)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.images', 'i')
            ->where('c.createdAt < :createdAt')
            ->andWhere('c.approved = 1')
            ->andWhere('c.expired = 0')
            ->setParameter(':createdAt', $classified->getCreatedAt())
            ->andWhere(':city MEMBER OF c.cities')
            ->setParameter(':city', $city)
            ->groupBy('c.id')
            ->setMaxResults(9)
            ->orderBy('c.createdAt', 'DESC');

        if ($type) {
            $qb->andWhere('c.type = :type')->setParameter(':type', $type);
        }

        if ($excludeBefore and count($this->related) > 0) {
            $qb->andWhere('c.id NOT IN (:classifieds)')->setParameter(':classifieds', $this->related);
        }

        $classifieds = $qb->getQuery()->getResult();

        $this->related = array_merge($this->related, $classifieds);

        return $classifieds;
    }

    /**
     * @param Type $type
     * @param int  $page
     *
     * @return Classified[]|PaginationInterface
     */
    public function getByType(Type $type, int $page)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.type = :type')->setParameter(':type', $type)
            ->andWhere('c.expired = 0')
            ->andWhere('c.approved = 1')
            ->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb, $page);
    }

    /**
     * @param City $city
     * @param Type $type
     * @param int  $page
     *
     * @return \App\AppBundle\Entity\Classified[]
     */
    public function findByCityAndType(City $city, Type $type, int $page)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where(':city MEMBER OF c.cities')
            ->setParameter(':city', $city)
            ->andWhere('c.type = :type')
            ->andWhere('c.approved = 1')
            ->setParameter(':type', $type)
            ->orderBy('c.refreshedAt', 'DESC');

        return $this->paginateQuery($qb, $page);
    }

    /**
     * @param string $query
     * @param int    $page
     *
     * @return \App\AppBundle\Entity\Classified[]
     */
    public function findBySearch(string $query, int $page)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere('c.title LIKE :query')
            ->orWhere('c.description LIKE :query')
            ->andWhere('c.approved = 1')
            ->andWhere('c.expired = 0')
            ->setParameter(':query', '%' . $query . '%')
            ->orderBy('c.createdAt', 'DESC');

        return $this->paginateQuery($qb, $page);
    }

    /**
     * @param int $perPageResults
     */
    public function setPerPageResults(int $perPageResults)
    {
        $this->perPageResults = $perPageResults;
    }

    /**
     * @param Classified $classified
     */
    public function remove(Classified $classified)
    {
        $this->_em->remove($classified);
        $this->_em->flush($classified);
    }

    /**
     * @param Classified $classified
     * @throws OptimisticLockException
     */
    public function incrementViews(Classified $classified)
    {
        $classified->setViews($classified->getViews() + 1);
        $this->save($classified);
    }

    /**
     * todo remove paginator from here as it's a view implementation and it has nothing to do with the persistance layer
     *
     * @param QueryBuilder $qb
     * @param int          $pageNo
     *
     * @return PaginationInterface
     */
    private function paginateQuery(QueryBuilder $qb, int $pageNo)
    {
        return $this->paginator->paginate(
            $qb,
            $pageNo,
            $this->perPageResults
        );
    }

    public function flush()
    {
        $this->_em->flush();
        $this->_em->clear();
    }

    /**
     * @param Classified $classified
     */
    public function saveBatch(Classified $classified)
    {
        $this->_em->persist($classified);
        $this->currentBatchSize++;

        if ($this->currentBatchSize % $this->batchSize == 0) {
            $this->_em->flush();
            $this->_em->clear();
        }
    }

    /**
     * @param string $titleSlug
     * @return Classified[]
     */
    public function findByStartingSlug($titleSlug)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.slug like :slug')
            ->andWhere('c.approved = 1')
            ->andWhere('c.expired = 0')
            ->setParameter(':slug', $titleSlug . '%');

        return $qb->getQuery()->getResult();
    }

    public function findAllMostRecent()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param DateTime $olderThan
     * @return Classified[]
     */
    public function findToBeReminded(DateTime $olderThan)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere('c.remindedAt < :olderThan')
            ->andWhere('c.expired = 0')
            ->andWhere('c.approved = 1')
            ->andWhere('c.deletedAt IS NULL')
            ->orderBy('c.remindedAt', 'ASC')
            ->andWhere('c.refreshedAt < :olderThan')
            ->setParameter('olderThan', $olderThan);

        return $qb->getQuery()->getResult();
    }
}
