<?php
declare(strict_types = 1);

namespace MigrationBundle\Services;

use AppBundle\Entity\Search;
use MigrationBundle\Repository\SearchRepository;
use DateTime;
use AppBundle\Entity\Repository\SearchRepository as DoctrineSearchRepository;

class SearchMigrator
{
    /**
     * @var SearchRepository
     */
    private $searchRepository;

    /**
     * @var DoctrineSearchRepository
     */
    private $doctrineSearchRepository;

    /**
     * @param SearchRepository         $searchRepository
     * @param DoctrineSearchRepository $doctrineSearchRepository
     */
    public function __construct(SearchRepository $searchRepository, DoctrineSearchRepository $doctrineSearchRepository)
    {
        $this->searchRepository = $searchRepository;
        $this->doctrineSearchRepository = $doctrineSearchRepository;
    }

    public function migrate()
    {
        print "Starting migrate searches ... " . PHP_EOL;
        foreach ($this->searchRepository->findAll() as $oldSearch) {
            $search = new Search();
            $search->setCount((int)$oldSearch['nr']);
            $search->setCreatedAt(new DateTime($oldSearch['created_at']));
            $search->setUpdatedAt(new DateTime($oldSearch['updated_at']));
            $search->setValid((bool)$oldSearch['valid']);
            $search->setQuery(utf8_decode(strtolower($oldSearch['q'])));

            $this->doctrineSearchRepository->saveBatch($search);
        }

        $this->doctrineSearchRepository->flush();
    }
}
