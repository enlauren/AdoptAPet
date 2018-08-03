<?php

namespace App\AppBundle\Services;

use function json_encode;
use Monolog\Formatter\ElasticaFormatter;
use function var_dump;

class MyFormatter extends ElasticaFormatter
{
    /**
     * @param string $index
     * @param string $type
     */
    public function __construct($index, $type)
    {
        parent::__construct($index, $type);
    }

    /**
     * @param array $record
     * @return array|\Elastica\Document|mixed|string
     */
    public function format(array $record)
    {
        $record['context'] = json_encode($record['context']);

        return parent::format($record);
    }

}
