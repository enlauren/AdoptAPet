<?php
declare(strict_types = 1);

namespace App\AppBundle\Services;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(PaginatorInterface $paginator, RequestStack $requestStack)
    {
        $this->paginator    = $paginator;
        $this->requestStack = $requestStack;
    }

    /**
     * @var int $perPage Per Page
     */
    private $perPage = 21;

    /**
     * @param $queryBuilder
     *
     * @return PaginationInterface
     */
    public function paginate(QueryBuilder $queryBuilder)
    {
        return $this->paginator->paginate(
            $queryBuilder,
            $this->requestStack->getCurrentRequest()->get('page', 1),
            $this->perPage
        );
    }
}
