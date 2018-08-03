<?php
declare(strict_types = 1);

namespace App\AppBundle\Interfaces;

interface LinkableInterface {
    public function getRoute();
    public function getIdentifier();
    public function getIdentifierName();
}
