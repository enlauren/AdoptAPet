<?php
declare(strict_types = 1);

namespace App\UserBundle\Exception;

use Exception;

class EmailAlreadyUsedException extends Exception
{
    public function __construct()
    {
        parent::__construct('User already registered with the provided email address.');
    }
}
