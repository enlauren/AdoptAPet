<?php
declare(strict_types = 1);

namespace App\AppBundle\Entity\Factory;

use App\AppBundle\Entity\Search;

class SearchFactory
{
    /**
     * @param string $query
     *
     * @return Search
     */
    public static function create(string $query): Search
    {
        $search = new Search();
        $search->setQuery($query);

        return $search;
    }
}
