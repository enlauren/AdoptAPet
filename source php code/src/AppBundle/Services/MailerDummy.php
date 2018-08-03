<?php

namespace App\AppBundle\Services;

use function var_dump;

class MailerDummy
{
    public function __call($name, $arguments)
    {
        var_dump($name);
        var_dump($arguments);
        die;
    }
}
